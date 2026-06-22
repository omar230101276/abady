@extends('layouts.app')

@section('title', 'Cinematography & Videos | Abady')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Title -->
    <div class="mb-12 border-b border-stone-200 pb-8">
        <span class="text-xs font-bold text-amber-750 uppercase tracking-widest block">Moving Frames Showcase</span>
        <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">CINEMATOGRAPHY REEL</h1>
    </div>

    @if($videos->isEmpty())
        <div class="bg-white border border-stone-200 rounded-xl p-16 text-center text-stone-400 text-sm shadow-sm">
            No video reels uploaded yet. Please check back later!
        </div>
    @else
        <!-- Video Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="videos-grid">
            @foreach($videos as $video)
                <div class="group bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-stone-300 transition-all duration-305 cursor-pointer"
                     data-video-title="{{ $video->title }}"
                     data-video-url="{{ $video->video_url }}"
                     data-video-file="{{ $video->file_url ?? '' }}"
                     onclick="openVideoModal(this)">
                     
                    <div>
                        <!-- Thumbnail/Image Area -->
                        <div class="relative h-56 bg-stone-100 flex items-center justify-center border-b border-stone-150 overflow-hidden">
                            @if($video->video_url)
                                @php
                                    $youtubeId = '';
                                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|win/|user/[^/]+/|embed/|watch\?v=)|youtu\.be/)([^"&?/\s]{11})%i', $video->video_url, $match)) {
                                        $youtubeId = $match[1];
                                    }
                                @endphp
                                @if($youtubeId)
                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" alt="YouTube Thumbnail" class="w-full h-full object-cover transition-all duration-550">
                                @else
                                    <i data-lucide="youtube" class="w-12 h-12 text-rose-600"></i>
                                @endif
                                <span class="absolute top-3 left-3 bg-red-100 text-red-805 px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-red-200">
                                    YouTube
                                </span>
                            @elseif($video->file_path)
                                <video src="{{ $video->file_url }}" preload="metadata" class="w-full h-full object-cover video-preview" muted playsinline></video>
                                <span class="absolute top-3 left-3 bg-indigo-100 text-indigo-805 px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-indigo-200 z-10">
                                    MP4 File
                                </span>
                            @else
                                <i data-lucide="video" class="w-12 h-12 text-indigo-750"></i>
                            @endif
                            <!-- Play Icon Overlay -->
                            <div class="absolute inset-0 bg-stone-950/15 group-hover:bg-stone-950/30 transition-colors flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-white/95 text-stone-950 flex items-center justify-center group-hover:scale-110 transition-transform shadow-md">
                                    <i data-lucide="play" class="w-6 h-6 fill-stone-950 ml-1"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5">
                            <h3 class="font-bold text-base text-stone-900 leading-snug group-hover:text-amber-700 transition-colors">
                                {{ $video->title }}
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Video Player Modal -->
<div id="video-modal" class="fixed inset-0 z-50 bg-white/98 backdrop-blur-md hidden flex-col items-center justify-center" role="dialog" aria-modal="true">
    <!-- Header -->
    <div class="absolute top-0 inset-x-0 h-20 px-6 flex items-center justify-between border-b border-stone-150/60 bg-white">
        <h4 id="video-modal-title" class="font-syne text-sm font-bold text-stone-900 tracking-wide uppercase">Video Title</h4>
        <button onclick="closeVideoModal()" class="p-3 text-stone-600 hover:text-stone-950 hover:bg-stone-50 rounded-full transition-colors">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Video Element Wrapper -->
    <div class="w-full max-w-4xl px-4 aspect-video flex items-center justify-center">
        <!-- YouTube IFrame Player -->
        <iframe id="modal-youtube-player" class="w-full h-full rounded border border-stone-200 shadow-lg hidden"
                src=""
                title="YouTube Video Player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>

        <!-- HTML5 Video Player -->
        <video id="modal-html5-player" class="w-full max-h-full rounded border border-stone-200 shadow-lg hidden" controls>
            <source src="" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openVideoModal(card) {
        const title = card.getAttribute('data-video-title');
        const videoUrl = card.getAttribute('data-video-url');
        const videoFile = card.getAttribute('data-video-file');

        document.getElementById('video-modal-title').textContent = title;

        const ytPlayer = document.getElementById('modal-youtube-player');
        const h5Player = document.getElementById('modal-html5-player');

        if (videoUrl) {
            // Parse YouTube ID
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = videoUrl.match(regExp);
            const ytId = (match && match[2].length === 11) ? match[2] : null;

            if (ytId) {
                ytPlayer.src = `https://www.youtube.com/embed/${ytId}?autoplay=1`;
                ytPlayer.classList.remove('hidden');
                h5Player.classList.add('hidden');
            }
        } else if (videoFile) {
            h5Player.src = videoFile;
            h5Player.classList.remove('hidden');
            ytPlayer.classList.add('hidden');
            h5Player.play();
        }

        const modal = document.getElementById('video-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
        const modal = document.getElementById('video-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';

        // Reset video players
        const ytPlayer = document.getElementById('modal-youtube-player');
        const h5Player = document.getElementById('modal-html5-player');

        ytPlayer.src = '';
        h5Player.pause();
        h5Player.src = '';
    }

    // Escape key listener to close modal
    window.addEventListener('keydown', (e) => {
        const modal = document.getElementById('video-modal');
        if (!modal.classList.contains('hidden') && e.key === 'Escape') {
            closeVideoModal();
        }
    });

    // Hover play/pause video previews
    document.querySelectorAll('.group').forEach(card => {
        const video = card.querySelector('.video-preview');
        if (video) {
            card.addEventListener('mouseenter', () => {
                video.play().catch(e => console.log("Preview autoplay blocked or interrupted:", e));
            });
            card.addEventListener('mouseleave', () => {
                video.pause();
                video.currentTime = 0;
            });
        }
    });
</script>
@endsection
