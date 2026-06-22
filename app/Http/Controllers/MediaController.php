<?php

namespace App\Http\Controllers;

use App\Models\Video;

class MediaController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index()
    {
        $videos = Video::latest()->get();
        return view('media', compact('videos'));
    }
}
