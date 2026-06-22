@extends('layouts.admin')

@section('title', 'Manage Collaboration: ' . $collaboration->name)

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.collaborations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-950 uppercase tracking-wider">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Collaborations
        </a>
    </div>

    <div class="max-w-2xl bg-white border border-stone-200 rounded-xl p-8 shadow-sm">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
            <i data-lucide="settings" class="w-5 h-5 text-stone-500"></i> Collaboration Details
        </h2>

        <form action="{{ route('admin.collaborations.update', $collaboration->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Collaborator Name / Brand</label>
                <input type="text" id="name" name="name" value="{{ old('name', $collaboration->name) }}" required
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="Name / Brand">
                @error('name')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                    placeholder="Describe the campaign or collaboration...">{{ old('description', $collaboration->description) }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Current Photo / Logo</label>
                <div class="mb-4 w-28 h-28 rounded-full overflow-hidden border border-stone-200 bg-stone-50 shadow-inner">
                    <img src="{{ $collaboration->thumbnail_url }}" alt="Current Photo" class="w-full h-full object-cover">
                </div>

                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Replace Photo / Logo (Optional)</label>
                <div class="border-2 border-dashed border-stone-200 rounded-lg p-4 bg-stone-50 hover:bg-stone-100 transition-colors cursor-pointer relative text-center">
                    <input type="file" id="image" name="image"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="updateFileName(this)">
                    <div class="space-y-2">
                        <i data-lucide="upload-cloud" class="w-8 h-8 text-stone-400 mx-auto"></i>
                        <p class="text-xs font-medium text-stone-600" id="file-name-placeholder">Click or drag logo/photo here</p>
                        <p class="text-[10px] text-stone-400">PNG, JPG or WEBP (Max. 10MB)</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors">
                <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('file-name-placeholder');
        if (input.files && input.files[0]) {
            placeholder.textContent = input.files[0].name;
            placeholder.classList.add('text-amber-700');
        } else {
            placeholder.textContent = 'Click or drag logo/photo here';
            placeholder.classList.remove('text-amber-700');
        }
    }
</script>
@endsection
