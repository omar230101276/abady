<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;

class CollaborationController extends Controller
{
    /**
     * Display a listing of collaborations.
     */
    public function index()
    {
        $collaborations = Collaboration::latest()->get();
        return view('collaborations', compact('collaborations'));
    }
}
