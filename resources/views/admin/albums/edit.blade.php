@extends('layouts.admin')

@section('title', 'Manage Album: ' . $album->title)

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.albums.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-950 uppercase tracking-wider">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Albums
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Edit Details Card -->
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm h-fit">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
                <i data-lucide="settings" class="w-5 h-5 text-stone-500"></i> Album Details
            </h2>

            <form action="{{ route('admin.albums.update', $album->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Album Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $album->title) }}" required
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                        placeholder="Album Title">
                    @error('title')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                        placeholder="Description">{{ old('description', $album->description) }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit" class="w-full py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Album Details
                </button>
            </form>
        </div>

        <!-- Photos Upload and Grid Panel -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Upload Photos Card -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
                <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-amber-600"></i> Add Photos to Album
                </h2>

                <form action="{{ route('admin.albums.photos.upload', $album->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <div class="border-2 border-dashed border-stone-200 rounded-lg p-6 bg-stone-50 hover:bg-stone-100 transition-colors cursor-pointer relative text-center">
                            <input type="file" id="photos" name="photos[]" multiple required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                onchange="updatePhotosCount(this)">
                            <div class="space-y-2">
                                <i data-lucide="images" class="w-10 h-10 text-stone-400 mx-auto"></i>
                                <p class="text-sm font-semibold text-stone-600" id="photos-name-placeholder">Select one or more photos to upload</p>
                                <p class="text-xs text-stone-400">PNG, JPG, JPEG, WEBP (Max. 15MB per file)</p>
                            </div>
                        </div>
                        @error('photos')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors">
                        <i data-lucide="upload" class="w-4 h-4"></i> Start Uploading
                    </button>
                </form>
            </div>

            <!-- Existing Photos Grid -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
                <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
                    <i data-lucide="images" class="w-5 h-5 text-stone-500"></i> Uploaded Photos ({{ $album->photos->count() }})
                </h2>

                @if($album->photos->isEmpty())
                    <div class="py-12 text-center text-stone-450 text-sm">
                        No photos uploaded to this album yet. Upload photos above.
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($album->photos as $photo)
                            <div class="relative group bg-stone-50 rounded-lg overflow-hidden border border-stone-200 aspect-square">
                                <img src="{{ $photo->thumbnail_url }}" alt="Photo" class="w-full h-full object-cover">
                                
                                <!-- Delete Overlay -->
                                <div class="absolute inset-0 bg-white/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <form action="{{ route('admin.albums.photos.destroy', [$album->id, $photo->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-600 text-white rounded-full hover:bg-rose-700 transition-colors shadow-md">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    function updatePhotosCount(input) {
        const placeholder = document.getElementById('photos-name-placeholder');
        if (input.files && input.files.length > 0) {
            placeholder.textContent = `${input.files.length} photo(s) selected`;
            placeholder.classList.add('text-amber-700');
        } else {
            placeholder.textContent = 'Select one or more photos to upload';
            placeholder.classList.remove('text-amber-700');
        }
    }
</script>
@endsection
