<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * Upload a file to Cloudinary and record it in the media_assets table.
     *
     * @param \Illuminate\Http\UploadedFile|string $file
     * @param string $subfolder Folder name (e.g. 'albums', 'photos')
     * @param string $resourceType 'image', 'video', or 'auto'
     * @return string Secure URL of the uploaded asset
     */
    public function uploadAndGetUrl($file, string $subfolder, string $resourceType = 'auto'): string
    {
        $filePath = is_string($file) ? $file : $file->getRealPath();
        $folder = 'photographer/' . trim($subfolder, '/');

        try {
            // Check if credentials are set
            if (!config('services.cloudinary.cloud_name') && !config('cloudinary.cloud_url')) {
                Log::warning('Cloudinary credentials are not configured. Falling back to local storage representation.');
                throw new \Exception('Cloudinary is not configured. Please add CLOUDINARY_URL or keys to .env');
            }

            // Upload using official Cloudinary SDK instance from container
            $cloudinary = app(\Cloudinary\Cloudinary::class);

            $result = $cloudinary->uploadApi()->upload($filePath, [
                'folder' => $folder,
                'resource_type' => $resourceType,
            ]);

            $secureUrl = $result['secure_url'];
            $publicId = $result['public_id'];
            $detectedType = $result['resource_type'] ?? ($resourceType === 'auto' ? 'image' : $resourceType);

            // Save metadata to media_assets
            MediaAsset::create([
                'cloudinary_public_id' => $publicId,
                'secure_url' => $secureUrl,
                'media_type' => $detectedType,
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null,
                'file_size' => $result['bytes'] ?? null,
            ]);

            return $secureUrl;
        } catch (\Exception $e) {
            Log::warning("Cloudinary upload failed ({$e->getMessage()}). Falling back to local storage.");

            if ($file instanceof \Illuminate\Http\UploadedFile) {
                // Store the uploaded file under 'public' storage disk
                return $file->store($subfolder, 'public');
            } elseif (is_string($file) && file_exists($file)) {
                // If it is a string path, copy the file to public storage
                $filename = basename($file);
                $targetDir = storage_path('app/public/' . $subfolder);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetPath = $targetDir . '/' . $filename;
                copy($file, $targetPath);
                return $subfolder . '/' . $filename;
            }

            throw $e;
        }
    }

    /**
     * Safely delete an asset from Cloudinary.
     *
     * @param string|null $url Secure URL of the asset to delete
     * @return bool True if deleted or wasn't a Cloudinary asset, false on failure
     */
    public function deleteByUrl(?string $url): bool
    {
        if (empty($url)) {
            return true;
        }

        // If it is not a Cloudinary asset
        if (!str_contains($url, 'res.cloudinary.com')) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($url)) {
                // Reference check for local files
                if ($this->isAssetReferencedElsewhere($url)) {
                    Log::info("Local asset safe deletion active: Asset '{$url}' is referenced elsewhere in DB. Skipping permanent deletion from storage.");
                    return true;
                }
                \Illuminate\Support\Facades\Storage::disk('public')->delete($url);
            }
            return true;
        }

        // Reference check: check if this URL is used anywhere else in the database
        if ($this->isAssetReferencedElsewhere($url)) {
            Log::info("Cloudinary asset safe deletion active: Asset '{$url}' is referenced elsewhere in DB. Skipping permanent deletion from Cloudinary.");
            return true; // Skipping delete but returning true (successful/safe bypass)
        }

        // Find the metadata record to get public ID and resource type
        $asset = MediaAsset::where('secure_url', $url)->first();
        if (!$asset) {
            // If not found in metadata table, try to extract public ID dynamically
            $publicId = $this->extractPublicId($url);
            $mediaType = str_contains($url, '/video/') ? 'video' : 'image';
        } else {
            $publicId = $asset->cloudinary_public_id;
            $mediaType = $asset->media_type;
        }

        if (!$publicId) {
            return false;
        }

        try {
            $cloudinary = app(\Cloudinary\Cloudinary::class);
            $cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $mediaType,
            ]);

            if ($asset) {
                $asset->delete();
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete Cloudinary asset {$publicId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a secure URL is referenced in any database columns.
     */
    public function isAssetReferencedElsewhere(string $url): bool
    {
        $count = 0;

        $count += \App\Models\Album::where('cover_image', $url)->count();
        $count += \App\Models\Photo::where('image_path', $url)->count();
        $count += \App\Models\Video::where('file_path', $url)->orWhere('video_url', $url)->count();
        $count += \App\Models\Collaboration::where('image', $url)->count();
        $count += \App\Models\Setting::where('key', 'bio_image')->where('value', $url)->count();

        return $count > 1;
    }

    /**
     * Extract public ID from a Cloudinary URL dynamically.
     */
    public function extractPublicId(string $url): ?string
    {
        // Matches: .../upload/v123456789/folder/subfolder/public_id.jpg
        // or .../upload/folder/subfolder/public_id.jpg
        if (preg_match('/\/upload\/(?:v\d+\/)?([^\.]+)/', $url, $matches)) {
            return urldecode($matches[1]);
        }
        return null;
    }

    /**
     * Generate an optimized URL for an image.
     */
    public function optimizeUrl(string $url, string $transformations = 'q_auto,f_auto'): string
    {
        if (!str_contains($url, 'res.cloudinary.com')) {
            return $url;
        }

        // Insert transformations right after '/upload/'
        return preg_replace('/\/upload\//', "/upload/{$transformations}/", $url, 1);
    }

    /**
     * Generate a square thumbnail URL.
     */
    public function getThumbnailUrl(string $url): string
    {
        return $this->optimizeUrl($url, 'c_fill,g_auto,w_400,h_400,q_auto,f_auto');
    }
}
