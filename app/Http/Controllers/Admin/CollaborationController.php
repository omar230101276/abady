<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Services\CloudinaryService;

class CollaborationController extends Controller
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    /**
     * Display a listing of collaborations.
     */
    public function index()
    {
        $collaborations = Collaboration::latest()->paginate(10);
        return view('admin.collaborations.index', compact('collaborations'));
    }

    /**
     * Show the form for creating a new collaboration.
     */
    public function create()
    {
        return view('admin.collaborations.create');
    }

    /**
     * Store a newly created collaboration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:10240', // Max 10MB
        ]);

        $path = $this->cloudinaryService->uploadAndGetUrl($request->file('image'), 'collaborations');

        Collaboration::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
        ]);

        return redirect()->route('admin.collaborations.index')->with('success', 'Collaboration added successfully.');
    }

    /**
     * Show the form for editing the specified collaboration.
     */
    public function edit(Collaboration $collaboration)
    {
        return view('admin.collaborations.edit', compact('collaboration'));
    }

    /**
     * Update the specified collaboration in storage.
     */
    public function update(Request $request, Collaboration $collaboration)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($collaboration->image) {
                $this->cloudinaryService->deleteByUrl($collaboration->image);
            }
            $data['image'] = $this->cloudinaryService->uploadAndGetUrl($request->file('image'), 'collaborations');
        }

        $collaboration->update($data);

        return redirect()->route('admin.collaborations.index')->with('success', 'Collaboration updated successfully.');
    }

    /**
     * Remove the specified collaboration from storage.
     */
    public function destroy(Collaboration $collaboration)
    {
        if ($collaboration->image) {
            $this->cloudinaryService->deleteByUrl($collaboration->image);
        }

        $collaboration->delete();

        return redirect()->route('admin.collaborations.index')->with('success', 'Collaboration deleted successfully.');
    }
}
