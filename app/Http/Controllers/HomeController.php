<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Video;

class HomeController extends Controller
{
    /**
     * Display the portfolio landing page.
     */
    public function index()
    {
        $featuredPhotos = Photo::with('album')->latest()->take(6)->get();
        $featuredVideos = Video::latest()->take(3)->get();

        return view('home', compact('featuredPhotos', 'featuredVideos'));
    }
}
