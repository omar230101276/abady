<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\MediaAsset;
use App\Models\Photo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class AdminCloudinaryMediaTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@abady.com',
            'password' => bcrypt('password'),
        ]);

        // Fake storage for local tests
        Storage::fake('public');
    }

    public function test_admin_can_create_album(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.albums.store'), [
            'title' => 'Cairo Street Portraits',
            'description' => 'A photo series in Egypt',
        ]);

        $response->assertRedirect(route('admin.albums.index'));
        $response->assertSessionHas('success');

        // Check album was saved in DB with null cover_image
        $this->assertDatabaseHas('albums', [
            'title' => 'Cairo Street Portraits',
            'cover_image' => null,
        ]);
    }

    public function test_admin_can_upload_photos_to_album_with_cloudinary(): void
    {
        $album = Album::create([
            'title' => 'Test Album',
            'cover_image' => 'https://res.cloudinary.com/demo/image/upload/cover.jpg',
        ]);

        $cloudinaryMock = $this->mock(\Cloudinary\Cloudinary::class);
        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $cloudinaryMock->shouldReceive('uploadApi')->andReturn($uploadApiMock);

        $uploadApiMock->shouldReceive('upload')
            ->once()
            ->with(Mockery::any(), Mockery::subset(['folder' => 'photographer/photos']))
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/photos/pic1.jpg',
                'public_id' => 'photographer/photos/pic1',
                'resource_type' => 'image',
                'width' => 1200,
                'height' => 800,
                'bytes' => 120000,
            ], []));

        $photoFile = UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.albums.photos.upload', $album->id), [
            'photos' => [$photoFile],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check photo URL in DB
        $this->assertDatabaseHas('photos', [
            'album_id' => $album->id,
            'image_path' => 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/photos/pic1.jpg',
        ]);

        // Check MediaAsset log in DB
        $this->assertDatabaseHas('media_assets', [
            'cloudinary_public_id' => 'photographer/photos/pic1',
            'secure_url' => 'https://res.cloudinary.com/demo/image/upload/v1571218681/photographer/photos/pic1.jpg',
        ]);
    }

    public function test_admin_can_upload_videos_with_cloudinary(): void
    {
        $cloudinaryMock = $this->mock(\Cloudinary\Cloudinary::class);
        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $cloudinaryMock->shouldReceive('uploadApi')->andReturn($uploadApiMock);

        $uploadApiMock->shouldReceive('upload')
            ->once()
            ->with(Mockery::any(), Mockery::subset(['folder' => 'photographer/videos', 'resource_type' => 'video']))
            ->andReturn(new \Cloudinary\Api\ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/demo/video/upload/v1571218681/photographer/videos/clip.mp4',
                'public_id' => 'photographer/videos/clip',
                'resource_type' => 'video',
                'bytes' => 15000000,
            ], []));

        // Fake mp4 upload file
        $videoFile = UploadedFile::fake()->create('clip.mp4', 5000, 'video/mp4');

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), [
            'title' => 'Cinematography Clip',
            'video_source' => 'file',
            'video_file' => $videoFile,
        ]);

        $response->assertRedirect(route('admin.videos.index'));
        $response->assertSessionHas('success');

        // Check video path saved with Cloudinary URL
        $this->assertDatabaseHas('videos', [
            'title' => 'Cinematography Clip',
            'file_path' => 'https://res.cloudinary.com/demo/video/upload/v1571218681/photographer/videos/clip.mp4',
        ]);

        // Check MediaAsset log in DB
        $this->assertDatabaseHas('media_assets', [
            'cloudinary_public_id' => 'photographer/videos/clip',
            'secure_url' => 'https://res.cloudinary.com/demo/video/upload/v1571218681/photographer/videos/clip.mp4',
            'media_type' => 'video',
            'file_size' => 15000000,
        ]);
    }

    public function test_safe_deletion_skips_cloudinary_if_referenced_elsewhere(): void
    {
        $sharedUrl = 'https://res.cloudinary.com/demo/image/upload/v1571218681/shared.jpg';

        // Create duplicate references in DB
        $album = Album::create([
            'title' => 'Album 1',
            'cover_image' => $sharedUrl,
        ]);

        $photo = Photo::create([
            'album_id' => $album->id,
            'image_path' => $sharedUrl,
        ]);

        MediaAsset::create([
            'cloudinary_public_id' => 'shared',
            'secure_url' => $sharedUrl,
            'media_type' => 'image',
        ]);

        // Mock Cloudinary - should NOT call destroy
        $cloudinaryMock = $this->mock(\Cloudinary\Cloudinary::class);
        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $cloudinaryMock->shouldReceive('uploadApi')->andReturn($uploadApiMock);

        $uploadApiMock->shouldNotReceive('destroy');

        // Delete photo
        $response = $this->actingAs($this->admin)->delete(route('admin.albums.photos.destroy', [$album->id, $photo->id]));
        
        $response->assertRedirect();
        
        // Assert photo record is gone
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);

        // Assert shared media asset is still present (as album cover references it)
        $this->assertDatabaseHas('media_assets', ['secure_url' => $sharedUrl]);
    }

    public function test_cloudinary_upload_fallback_to_local_storage_when_credentials_not_configured(): void
    {
        // Set config to null
        config(['services.cloudinary.cloud_name' => null]);
        config(['cloudinary.cloud_url' => null]);

        $album = Album::create([
            'title' => 'Test Album',
        ]);

        $photoFile = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        // This will attempt to upload to Cloudinary, fail because no credentials, and fall back to local storage
        $response = $this->actingAs($this->admin)->post(route('admin.albums.photos.upload', $album->id), [
            'photos' => [$photoFile],
        ]);

        $response->assertRedirect();
        
        // Assert that the file is stored locally under public disk
        $photo = Photo::where('album_id', $album->id)->first();
        $this->assertNotNull($photo);
        $this->assertStringStartsWith('photos/', $photo->image_path);
        
        // Check file exists in faked storage
        Storage::disk('public')->assertExists($photo->image_path);
    }

    public function test_cloudinary_upload_fallback_to_local_storage_on_sdk_exception(): void
    {
        // Mock Cloudinary to throw Exception on upload
        $cloudinaryMock = $this->mock(\Cloudinary\Cloudinary::class);
        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $cloudinaryMock->shouldReceive('uploadApi')->andReturn($uploadApiMock);

        $uploadApiMock->shouldReceive('upload')
            ->once()
            ->andThrow(new \Exception('Cloudinary Quota Exceeded'));

        $album = Album::create([
            'title' => 'Test Album',
        ]);

        $photoFile = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post(route('admin.albums.photos.upload', $album->id), [
            'photos' => [$photoFile],
        ]);

        $response->assertRedirect();

        // Assert file fallback
        $photo = Photo::where('album_id', $album->id)->first();
        $this->assertNotNull($photo);
        $this->assertStringStartsWith('photos/', $photo->image_path);
        Storage::disk('public')->assertExists($photo->image_path);
    }

    public function test_local_file_safe_deletion(): void
    {
        $album = Album::create([
            'title' => 'Test Album',
        ]);

        // Upload a file locally (simulate fallback)
        $photoFile = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');
        $localPath = Storage::disk('public')->putFile('photos', $photoFile);

        $photo = Photo::create([
            'album_id' => $album->id,
            'image_path' => $localPath,
        ]);

        Storage::disk('public')->assertExists($localPath);

        // Delete photo via controller
        $response = $this->actingAs($this->admin)->delete(route('admin.albums.photos.destroy', [$album->id, $photo->id]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);

        // Assert file is deleted from local disk
        Storage::disk('public')->assertMissing($localPath);
    }
}
