@extends('layouts.admin')

@section('title', 'Manage Videos')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Videos List Grid -->
    <div class="lg:col-span-2 space-y-6">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2">
            <i data-lucide="video" class="w-5 h-5 text-stone-500"></i> Video Gallery
        </h2>

        @if($videos->isEmpty())
            <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm shadow-sm">
                No videos added yet. Add links or upload files using the panel on the right.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($videos as $video)
                    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-stone-300 transition-colors">
                        <div>
                            <!-- Video Type Header/Indicator -->
                            <div class="relative h-40 bg-stone-100 flex items-center justify-center border-b border-stone-100">
                                @if($video->video_url)
                                    @php
                                        $youtubeId = '';
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|win/|user/[^/]+/|embed/|watch\?v=)|youtu\.be/)([^"&?/\s]{11})%i', $video->video_url, $match)) {
                                            $youtubeId = $match[1];
                                        }
                                    @endphp
                                    @if($youtubeId)
                                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" alt="YouTube Thumbnail" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="youtube" class="w-12 h-12 text-rose-600"></i>
                                    @endif
                                    <span class="absolute top-3 left-3 bg-rose-100 text-rose-800 px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-rose-200">
                                        Link Embed
                                    </span>
                                @else
                                    <i data-lucide="video" class="w-12 h-12 text-indigo-650"></i>
                                    <span class="absolute top-3 left-3 bg-indigo-100 text-indigo-800 px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-indigo-200">
                                        Local MP4
                                    </span>
                                @endif
                                <div class="absolute inset-0 bg-stone-900/10 flex items-center justify-center">
                                    <i data-lucide="play" class="w-10 h-10 text-white drop-shadow-md"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-bold text-sm text-stone-900 leading-snug">{{ $video->title }}</h3>
                                <p class="text-[10px] text-stone-400 mt-2 truncate max-w-full font-mono bg-stone-50 p-2 rounded">
                                    {{ $video->video_url ?? $video->file_path }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-5 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50">
                            <a href="{{ route('admin.videos.edit', $video->id) }}" class="flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-stone-950 uppercase tracking-wider">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit Video
                            </a>
                            
                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
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
                {{ $videos->links() }}
            </div>
        @endif
    </div>

    <!-- Add Video Panel -->
    <div id="add-video" class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm h-fit">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-650"></i> Add Video
        </h2>

        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="video-form">
            @csrf

            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Video Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="e.g. Summer Cinematic B-Roll">
                @error('title')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="h-px bg-stone-200 my-6"></div>

            <!-- Method Selection -->
            <div class="space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">Choose Video Source</label>
                
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="video_source" value="link" checked class="text-stone-905 focus:ring-stone-905" onchange="toggleSource(this.value)">
                        <span class="ml-2 text-sm text-stone-700 font-medium">Link Embed (YouTube)</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="video_source" value="file" class="text-stone-905 focus:ring-stone-905" onchange="toggleSource(this.value)">
                        <span class="ml-2 text-sm text-stone-700 font-medium">Local MP4 File</span>
                    </label>
                </div>
            </div>

            <!-- YouTube/Vimeo Link Group -->
            <div id="group-link" class="space-y-2">
                <label for="video_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">YouTube or Vimeo URL</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="https://www.youtube.com/watch?v=...">
                @error('video_url')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- MP4 File Group (hidden by default) -->
            <div id="group-file" class="space-y-2 hidden">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Upload Video File (MP4)</label>
                <div class="border-2 border-dashed border-stone-200 rounded-lg p-4 bg-stone-50 hover:bg-stone-100 transition-colors cursor-pointer relative text-center">
                    <input type="file" id="video_file" name="video_file"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="updateFileName(this)">
                    <div class="space-y-2">
                        <i data-lucide="video" class="w-8 h-8 text-stone-400 mx-auto"></i>
                        <p class="text-xs font-medium text-stone-600" id="file-name-placeholder">Click or drag MP4 video here</p>
                        <p class="text-[10px] text-stone-400">MP4 format (Max. 50MB)</p>
                    </div>
                </div>
                @error('video_file')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Video
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleSource(val) {
        const groupLink = document.getElementById('group-link');
        const groupFile = document.getElementById('group-file');
        
        const videoUrlInput = document.getElementById('video_url');
        const videoFileInput = document.getElementById('video_file');

        if (val === 'link') {
            groupLink.classList.remove('hidden');
            groupFile.classList.add('hidden');
            videoFileInput.value = ''; // Clear file
            document.getElementById('file-name-placeholder').textContent = 'Click or drag MP4 video here';
            document.getElementById('file-name-placeholder').classList.remove('text-indigo-600');
        } else {
            groupLink.classList.add('hidden');
            groupFile.classList.remove('hidden');
            videoUrlInput.value = ''; // Clear url
        }
    }

    function updateFileName(input) {
        const placeholder = document.getElementById('file-name-placeholder');
        if (input.files && input.files[0]) {
            placeholder.textContent = input.files[0].name;
            placeholder.classList.add('text-indigo-600');
        } else {
            placeholder.textContent = 'Click or drag MP4 video here';
            placeholder.classList.remove('text-indigo-600');
        }
    }
</script>
@endsection
