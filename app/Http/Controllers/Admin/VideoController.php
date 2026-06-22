<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\CloudinaryService;

class VideoController extends Controller
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    /**
     * Display a listing of the videos.
     */
    public function index()
    {
        $videos = Video::latest()->paginate(10);
        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:51200', // Max 50MB
        ]);

        if (!$request->video_url && !$request->hasFile('video_file')) {
            return back()->withErrors([
                'video_url' => 'You must provide either a video link (YouTube/Vimeo) or upload a local video file (MP4).'
            ])->withInput();
        }

        $filePath = null;
        if ($request->hasFile('video_file')) {
            $filePath = $this->cloudinaryService->uploadAndGetUrl($request->file('video_file'), 'videos', 'video');
        }

        Video::create([
            'title' => $request->title,
            'video_url' => $request->video_url,
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|url|max:255',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:51200',
        ]);

        if (!$request->video_url && !$request->hasFile('video_file') && !$video->file_path) {
            return back()->withErrors([
                'video_url' => 'You must provide either a video link (YouTube/Vimeo) or upload a local video file (MP4).'
            ])->withInput();
        }

        $data = [
            'title' => $request->title,
            'video_url' => $request->video_url,
        ];

        if ($request->hasFile('video_file')) {
            if ($video->file_path) {
                $this->cloudinaryService->deleteByUrl($video->file_path);
            }
            $data['file_path'] = $this->cloudinaryService->uploadAndGetUrl($request->file('video_file'), 'videos', 'video');
            $data['video_url'] = null; // Clear URL if a file is uploaded
        } elseif ($request->video_url) {
            if ($video->file_path) {
                $this->cloudinaryService->deleteByUrl($video->file_path);
                $data['file_path'] = null; // Clear file if a URL is provided
            }
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy(Video $video)
    {
        if ($video->file_path) {
            $this->cloudinaryService->deleteByUrl($video->file_path);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}
