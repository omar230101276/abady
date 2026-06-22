@extends('layouts.admin')

@section('title', 'Manage Albums')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Albums List Grid -->
    <div class="lg:col-span-2 space-y-6">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2">
            <i data-lucide="folders" class="w-5 h-5 text-stone-500"></i> Existing Albums
        </h2>

        @if($albums->isEmpty())
            <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm shadow-sm">
                No albums created yet. Use the form on the right to start!
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($albums as $album)
                    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-stone-300 transition-colors">
                        <div>
                            <!-- Album Cover -->
                            <div class="relative h-48 bg-stone-100 overflow-hidden">
                                <img src="{{ $album->cover_thumbnail_url }}" alt="{{ $album->title }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-3 right-3 bg-stone-950/85 backdrop-blur-sm text-white px-2.5 py-1 text-xs font-semibold rounded">
                                    {{ $album->photos_count }} {{ Str::plural('photo', $album->photos_count) }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-bold text-base text-stone-900 leading-snug">{{ $album->title }}</h3>
                                <p class="text-xs text-stone-550 mt-2 line-clamp-3 leading-relaxed">
                                    {{ $album->description ?? 'No description provided.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50">
                            <a href="{{ route('admin.albums.edit', $album->id) }}" class="flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-stone-950 uppercase tracking-wider">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Manage Photos
                            </a>
                            
                            <form action="{{ route('admin.albums.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this album and all its photos?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-1.5 text-xs font-bold text-rose-700 hover:text-rose-900 uppercase tracking-wider">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $albums->links() }}
            </div>
        @endif
    </div>

    <!-- Create Album Card -->
    <div id="create-album" class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm h-fit">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
            <i data-lucide="folder-plus" class="w-5 h-5 text-amber-600"></i> Create Album
        </h2>

        <form action="{{ route('admin.albums.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Album Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="e.g. Portraits in Cairo">
                @error('title')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                    placeholder="Describe this photo series..."></textarea>
                @error('description')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>


            <button type="submit" class="w-full py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Album
            </button>
        </form>
    </div>
</div>
@endsection


