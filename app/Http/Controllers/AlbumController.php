<?php

namespace App\Http\Controllers;

use App\Models\Album;

class AlbumController extends Controller
{
    /**
     * Display the albums and photo galleries.
     */
    public function index()
    {
        $albums = Album::with('photos')->latest()->get();
        return view('albums', compact('albums'));
    }
}
