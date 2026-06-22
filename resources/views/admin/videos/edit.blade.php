@extends('layouts.admin')

@section('title', 'Edit Video: ' . $video->title)

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.videos.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-950 uppercase tracking-wider">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Videos
        </a>
    </div>

    <div class="max-w-2xl bg-white border border-stone-200 rounded-xl p-8 shadow-sm">
        <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
            <i data-lucide="settings" class="w-5 h-5 text-stone-500"></i> Video Settings
        </h2>

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Video Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $video->title) }}" required
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="Video Title">
                @error('title')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="h-px bg-stone-200"></div>

            <!-- Current source info -->
            <div class="p-4 bg-stone-50 rounded-lg border border-stone-200 text-xs text-stone-700">
                <span class="font-bold">Current Source:</span>
                @if($video->video_url)
                    <span class="ml-1.5 px-2 py-0.5 bg-rose-100 text-rose-800 rounded font-semibold">Embedded link</span>
                    <p class="mt-2 font-mono bg-white p-2.5 rounded border border-stone-150 overflow-x-auto">{{ $video->video_url }}</p>
                @else
                    <span class="ml-1.5 px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded font-semibold">Local upload</span>
                    <p class="mt-2 font-mono bg-white p-2.5 rounded border border-stone-150 overflow-x-auto">{{ $video->file_path }}</p>
                @endif
            </div>

            <!-- Method Selection -->
            <div class="space-y-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">Change Video Source</label>
                
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="video_source" value="link" {{ $video->video_url ? 'checked' : '' }} class="text-stone-905 focus:ring-stone-905" onchange="toggleSource(this.value)">
                        <span class="ml-2 text-sm text-stone-700 font-medium">Link Embed (YouTube)</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="video_source" value="file" {{ $video->file_path ? 'checked' : '' }} class="text-stone-905 focus:ring-stone-905" onchange="toggleSource(this.value)">
                        <span class="ml-2 text-sm text-stone-700 font-medium">Local MP4 File</span>
                    </label>
                </div>
            </div>

            <!-- YouTube/Vimeo Link Group -->
            <div id="group-link" class="space-y-2 {{ $video->video_url ? '' : 'hidden' }}">
                <label for="video_url" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">YouTube URL</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                    placeholder="https://www.youtube.com/watch?v=...">
                @error('video_url')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- MP4 File Group -->
            <div id="group-file" class="space-y-2 {{ $video->file_path ? '' : 'hidden' }}">
                <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Replace Video File (MP4)</label>
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

            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors">
                <i data-lucide="save" class="w-4 h-4"></i> Save Settings
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
            videoFileInput.value = '';
            document.getElementById('file-name-placeholder').textContent = 'Click or drag MP4 video here';
            document.getElementById('file-name-placeholder').classList.remove('text-indigo-600');
        } else {
            groupLink.classList.add('hidden');
            groupFile.classList.remove('hidden');
            videoUrlInput.value = '';
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
