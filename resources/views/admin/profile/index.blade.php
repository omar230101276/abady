@extends('layouts.admin')

@section('title', 'Admin Profile & Biography')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-stone-200 pb-6 flex items-center justify-between">
        <div class="space-y-1">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 text-2xl">
                <i data-lucide="user" class="w-6 h-6 text-stone-500"></i> Profile Settings
            </h2>
            <p class="text-xs text-stone-500 font-light font-medium">Update your admin login credentials and homepage biography / portfolio portrait picture.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-805 p-4 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check" class="w-4 h-4 text-emerald-600 bg-emerald-100 p-0.5 rounded-full"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: Login Credentials -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                    <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                        <i data-lucide="lock" class="w-4 h-4 text-stone-400"></i> Login Credentials
                    </h3>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">Admin Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                        @error('email')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t border-stone-100 pt-4 space-y-4">
                        <p class="text-[10px] text-stone-450 leading-relaxed font-light">Leave password fields blank if you do not want to update your password.</p>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">New Password</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                            @error('password')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                <!-- Public Contact Settings Card -->
                <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                    <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                        <i data-lucide="mail" class="w-4 h-4 text-stone-400"></i> Contact Settings
                    </h3>

                    <!-- Contact Email -->
                    <div>
                        <label for="contact_email" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">Public Website Email</label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $contactEmail) }}" required
                            class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="hello@abady.com">
                        <p class="text-[10px] text-stone-405 mt-1.5 font-light leading-relaxed">This email is displayed publicly across all pages (footer, contact details).</p>
                        @error('contact_email')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Location -->
                    <div>
                        <label for="contact_location" class="block text-xs font-bold uppercase tracking-wider text-stone-705 mb-2">Public Website Location</label>
                        <input type="text" id="contact_location" name="contact_location" value="{{ old('contact_location', $contactLocation) }}" required
                            class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="Cairo, Egypt">
                        <p class="text-[10px] text-stone-405 mt-1.5 font-light leading-relaxed">This location is displayed publicly across all pages (footer, contact details).</p>
                        @error('contact_location')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Side: Biography & Portrait -->
            <div class="lg:col-span-8 bg-white border border-stone-200 p-8 rounded-xl shadow-sm space-y-6">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="camera" class="w-4 h-4 text-stone-400"></i> Biography & Home Section
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    
                    <!-- Portrait Image Upload -->
                    <div class="md:col-span-4 space-y-4">
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-705">Bio Portrait Photo</label>
                        <div class="aspect-[4/5] w-full bg-stone-50 border border-stone-200 rounded-lg overflow-hidden relative shadow-sm">
                            <img id="image-preview" src="{{ str_starts_with($bioImage, 'http') ? $bioImage : (str_starts_with($bioImage, 'images/') ? asset($bioImage) : asset('storage/' . $bioImage)) }}" 
                                alt="Bio Portrait Preview" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <input type="file" id="bio_image" name="bio_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <button type="button" onclick="document.getElementById('bio_image').click()" 
                                class="w-full py-2 border border-stone-200 hover:border-stone-300 text-stone-700 hover:text-stone-950 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center justify-center gap-2 bg-stone-50">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Change Photo
                            </button>
                            @error('bio_image')
                                <p class="text-rose-600 text-xs mt-1.5 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Texts Editing -->
                    <div class="md:col-span-8 space-y-6">
                        <!-- Bio Title -->
                        <div>
                            <label for="bio_title" class="block text-xs font-bold uppercase tracking-wider text-stone-755 mb-2">Bio Header / Title</label>
                            <input type="text" id="bio_title" name="bio_title" value="{{ old('bio_title', $bioTitle) }}" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all font-semibold">
                            @error('bio_title')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio Intro Paragraph -->
                        <div>
                            <label for="bio_intro" class="block text-xs font-bold uppercase tracking-wider text-stone-755 mb-2">Introduction Paragraph</label>
                            <textarea id="bio_intro" name="bio_intro" rows="4" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none leading-relaxed font-light">{{ old('bio_intro', $bioIntro) }}</textarea>
                            @error('bio_intro')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio Detailed Paragraph -->
                        <div>
                            <label for="bio_description" class="block text-xs font-bold uppercase tracking-wider text-stone-755 mb-2">Biography Detailed Paragraph</label>
                            <textarea id="bio_description" name="bio_description" rows="5" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none leading-relaxed font-light">{{ old('bio_description', $bioDescription) }}</textarea>
                            @error('bio_description')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Social Media Links -->
                        <div class="border-t border-stone-100 pt-6 space-y-4">
                            <h4 class="font-syne text-[11px] uppercase font-bold text-stone-705 tracking-wider flex items-center gap-1.5">
                                <i data-lucide="share-2" class="w-4 h-4 text-stone-400"></i> Social Profiles
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Instagram -->
                                <div>
                                    <label for="social_instagram" class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1.5">Instagram URL</label>
                                    <input type="text" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $socialInstagram) }}" placeholder="https://instagram.com/username"
                                        class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                                    @error('social_instagram')
                                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- YouTube -->
                                <div>
                                    <label for="social_youtube" class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1.5">YouTube URL</label>
                                    <input type="text" id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $socialYoutube) }}" placeholder="https://youtube.com/@channel"
                                        class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                                    @error('social_youtube')
                                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- TikTok -->
                                <div>
                                    <label for="social_tiktok" class="block text-[10px] font-bold uppercase tracking-wider text-stone-500 mb-1.5">TikTok URL</label>
                                    <input type="text" id="social_tiktok" name="social_tiktok" value="{{ old('social_tiktok', $socialTiktok) }}" placeholder="https://tiktok.com/@username"
                                        class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                                    @error('social_tiktok')
                                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-t border-stone-100 pt-6 flex justify-end">
                    <button type="submit" class="py-3 px-8 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Profile Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
