<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\CloudinaryService;

class AlbumController extends Controller
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    /**
     * Display a listing of the albums.
     */
    public function index()
    {
        $albums = Album::withCount('photos')->latest()->paginate(10);
        return view('admin.albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new album.
     */
    public function create()
    {
        return view('admin.albums.create');
    }

    /**
     * Store a newly created album in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Album::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.albums.index')->with('success', 'Album created successfully.');
    }

    /**
     * Show the form for editing the specified album.
     */
    public function edit(Album $album)
    {
        $album->load('photos');
        return view('admin.albums.edit', compact('album'));
    }

    /**
     * Update the specified album in storage.
     */
    public function update(Request $request, Album $album)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $album->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.albums.index')->with('success', 'Album updated successfully.');
    }

    /**
     * Remove the specified album from storage.
     */
    public function destroy(Album $album)
    {
        // Delete all photos files
        foreach ($album->photos as $photo) {
            $this->cloudinaryService->deleteByUrl($photo->image_path);
            $photo->delete();
        }

        // Delete cover image
        if ($album->cover_image) {
            $this->cloudinaryService->deleteByUrl($album->cover_image);
        }

        $album->delete();

        return redirect()->route('admin.albums.index')->with('success', 'Album deleted successfully.');
    }

    /**
     * Upload photos to the specified album.
     */
    public function uploadPhotos(Request $request, Album $album)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|max:15360', // Max 15MB per photo
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $this->cloudinaryService->uploadAndGetUrl($file, 'photos');
                $album->photos()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Photos uploaded successfully.');
    }

    /**
     * Delete a specific photo from an album.
     */
    public function deletePhoto(Album $album, Photo $photo)
    {
        if ($photo->album_id === $album->id) {
            $this->cloudinaryService->deleteByUrl($photo->image_path);
            $photo->delete();
            return back()->with('success', 'Photo deleted successfully.');
        }

        return back()->with('error', 'Unauthorized operation.');
    }
}
