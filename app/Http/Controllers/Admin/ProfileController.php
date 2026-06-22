<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Services\CloudinaryService;

class ProfileController extends Controller
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }
    /**
     * Show the admin profile edit form.
     */
    public function index()
    {
        $user = auth()->user();
        
        $bioTitle = Setting::get('bio_title', 'Documenting authentic frames & human identities.');
        $bioIntro = Setting::get('bio_intro', '');
        $bioDescription = Setting::get('bio_description', '');
        $bioImage = Setting::get('bio_image', 'images/portrait.png');
        
        $socialInstagram = Setting::get('social_instagram', '');
        $socialYoutube = Setting::get('social_youtube', '');
        $socialTiktok = Setting::get('social_tiktok', '');
        
        $contactEmail = Setting::get('contact_email', 'hello@abady.com');
        $contactLocation = Setting::get('contact_location', 'Cairo, Egypt');

        return view('admin.profile.index', compact('user', 'bioTitle', 'bioIntro', 'bioDescription', 'bioImage', 'socialInstagram', 'socialYoutube', 'socialTiktok', 'contactEmail', 'contactLocation'));
    }

    /**
     * Update the admin profile and homepage bio section.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'bio_title' => 'required|string|max:255',
            'bio_intro' => 'required|string',
            'bio_description' => 'required|string',
            'bio_image' => 'nullable|image|max:524288', // max 512MB
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_location' => 'required|string|max:255',
        ]);

        // Update login details
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update bio texts settings
        Setting::set('bio_title', $request->bio_title);
        Setting::set('bio_intro', $request->bio_intro);
        Setting::set('bio_description', $request->bio_description);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_location', $request->contact_location);
        
        // Update social links
        Setting::set('social_instagram', $request->social_instagram ?? '');
        Setting::set('social_youtube', $request->social_youtube ?? '');
        Setting::set('social_tiktok', $request->social_tiktok ?? '');

        // Handle bio image upload
        if ($request->hasFile('bio_image')) {
            // Delete old bio image if not default
            $oldImage = Setting::get('bio_image');
            if ($oldImage && !str_starts_with($oldImage, 'images/')) {
                if (str_contains($oldImage, 'res.cloudinary.com')) {
                    $this->cloudinaryService->deleteByUrl($oldImage);
                } else {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Store new image
            $path = $this->cloudinaryService->uploadAndGetUrl($request->file('bio_image'), 'profile');
            Setting::set('bio_image', $path);
        }

        return back()->with('success', 'Profile and homepage bio section updated successfully.');
    }
}
