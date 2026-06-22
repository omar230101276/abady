@extends('layouts.app')

@section('title', 'Abady | Professional Photographer & Visual Storyteller')

@section('content')
<!-- Full-Screen Hero Section -->
<section class="relative h-[90vh] flex items-center justify-center overflow-hidden bg-stone-100">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.png') }}" alt="Abady Photography Hero" class="w-full h-full object-cover object-center filter grayscale opacity-90 scale-105 transition-transform duration-1000">
        <!-- Light Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-transparent to-black/10"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto space-y-6">
        <span class="text-xs font-bold uppercase tracking-[0.35em] text-stone-600 block">Fine-Art & Editorial Photography</span>
        <h1 class="font-syne text-6xl md:text-8xl font-extrabold tracking-tight text-stone-950 uppercase leading-none drop-shadow-sm">
            ABADY<span class="text-amber-600">.</span>
        </h1>
        <p class="text-stone-700 text-base md:text-lg max-w-xl mx-auto font-light leading-relaxed">
            Capturing high-fashion, commercial campaigns, and raw human expressions through organic contrast and minimalist design.
        </p>
        <div class="pt-4">
            <a href="{{ route('albums') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white bg-stone-950 px-8 py-4 hover:bg-stone-850 hover:gap-3 transition-all rounded-sm shadow-sm">
                View Portfolio <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</section>

<!-- Bio Section -->
<section class="py-24 bg-white border-y border-stone-200/60">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
        @php
            $bioImage = \App\Models\Setting::get('bio_image', 'images/portrait.png');
            if (str_starts_with($bioImage, 'http')) {
                $bioImagePath = app(\App\Services\CloudinaryService::class)->optimizeUrl($bioImage, 'c_limit,w_600,q_auto,f_auto');
            } else {
                $bioImagePath = str_starts_with($bioImage, 'images/') ? asset($bioImage) : asset('storage/' . $bioImage);
            }
            $bioTitle = \App\Models\Setting::get('bio_title', 'Documenting authentic frames & human identities.');
            $bioIntro = \App\Models\Setting::get('bio_intro', 'I am Abady, a professional photographer and cinematographer based in Egypt, operating globally. My philosophy is rooted in minimal structures, organic lighting, and high-fashion aesthetics.');
            $bioDescription = \App\Models\Setting::get('bio_description', 'With over a decade of capturing visual narratives, my work spans commercial editorial campaigns, minimalist street reportages, and cinematic storytelling. I collaborate with brands, designers, and curators who seek to convey organic depth and elevated aesthetics.');
            $socialInstagram = \App\Models\Setting::get('social_instagram', '#') ?: '#';
            $socialYoutube = \App\Models\Setting::get('social_youtube', '#') ?: '#';
            $socialTiktok = \App\Models\Setting::get('social_tiktok', '#') ?: '#';
        @endphp
        <!-- Bio Image -->
        <div class="lg:col-span-5 relative group">
            <div class="absolute -inset-2 bg-gradient-to-tr from-amber-600/10 to-transparent blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            <div class="relative aspect-[4/5] bg-stone-50 overflow-hidden border border-stone-200 shadow-sm rounded-lg">
                <img src="{{ $bioImagePath }}" alt="Abady Editorial Portrait" class="w-full h-full object-cover filter hover:scale-102 transition-transform duration-700">
            </div>
        </div>

        <!-- Bio Content -->
        <div class="lg:col-span-7 space-y-8">
            <div class="space-y-3">
                <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">The Artist Behind the Lens</span>
                <h2 class="font-syne text-3xl md:text-4xl font-extrabold text-stone-900 leading-tight">
                    {{ $bioTitle }}
                </h2>
            </div>
            
            <div class="text-stone-600 text-sm md:text-base leading-relaxed space-y-4 font-light whitespace-pre-line">
                <p>
                    {{ $bioIntro }}
                </p>
                <p>
                    {{ $bioDescription }}
                </p>
            </div>

            <!-- Connect & Action -->
            <div class="pt-6 border-t border-stone-100 flex flex-wrap items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <span class="text-xs font-bold text-stone-400 uppercase tracking-widest">Connect</span>
                    <div class="flex items-center gap-4">
                        <a href="{{ $socialInstagram }}" target="_blank" class="text-stone-600 hover:text-amber-750 transition-colors p-2 hover:bg-stone-50 rounded-full flex items-center justify-center" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="{{ $socialYoutube }}" target="_blank" class="text-stone-600 hover:text-amber-750 transition-colors p-2 hover:bg-stone-50 rounded-full flex items-center justify-center" aria-label="YouTube">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                            </svg>
                        </a>
                        <a href="{{ $socialTiktok }}" target="_blank" class="text-stone-600 hover:text-amber-750 transition-colors p-2 hover:bg-stone-50 rounded-full flex items-center justify-center" aria-label="TikTok">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-950 hover:text-amber-755 uppercase tracking-wider group">
                    Start a Collaboration <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Gallery Section -->
<section class="py-24 bg-stone-50">
    <div class="max-w-7xl mx-auto px-6 space-y-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-2">
                <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Featured Work</span>
                <h2 class="font-syne text-3xl font-bold text-stone-900">Latest Photo Collections</h2>
            </div>
            <a href="{{ route('albums') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-950 hover:text-amber-700 uppercase tracking-widest group">
                View All Albums <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        @if($featuredPhotos->isEmpty())
            <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm">
                No featured photos uploaded yet. Check back soon!
            </div>
        @else
            <!-- Photo Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featuredPhotos as $photo)
                    <div class="group relative overflow-hidden bg-stone-100 aspect-square border border-stone-200 shadow-sm rounded-lg">
                        <img src="{{ $photo->thumbnail_url }}" alt="Featured work" class="w-full h-full object-cover transition-all duration-700 ease-out group-hover:scale-102">
                        <!-- Card Hover Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                            <div>
                                <span class="text-[10px] text-amber-400 font-bold uppercase tracking-wider">Album: {{ $photo->album->title }}</span>
                                <h4 class="text-white font-syne font-bold text-sm mt-1">Captured Frame</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Featured Videos Section -->
<section class="py-24 bg-white border-t border-stone-200/60">
    <div class="max-w-7xl mx-auto px-6 space-y-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-2">
                <span class="text-xs font-bold text-amber-750 uppercase tracking-widest block">Moving Frames</span>
                <h2 class="font-syne text-3xl font-bold text-stone-900">Cinematography Showcase</h2>
            </div>
            <a href="{{ route('media') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-950 hover:text-amber-700 uppercase tracking-widest group">
                Explore Video Library <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        @if($featuredVideos->isEmpty())
            <div class="bg-stone-50 border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm">
                No videos uploaded yet. Cinematography showcase is empty.
            </div>
        @else
            <!-- Video Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredVideos as $video)
                    <div class="group bg-stone-50 border border-stone-200 rounded-lg overflow-hidden shadow-sm flex flex-col justify-between hover:border-stone-300 transition-all duration-300 cursor-pointer"
                         data-video-title="{{ $video->title }}"
                         data-video-url="{{ $video->video_url }}"
                         data-video-file="{{ $video->file_url ?? '' }}"
                         onclick="openVideoModal(this)">
                        <div>
                            <!-- Video Thumbnail Area -->
                            <div class="relative h-48 bg-stone-100 flex items-center justify-center border-b border-stone-150">
                                @if($video->video_url)
                                    @php
                                        $youtubeId = '';
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|win/|user/[^/]+/|embed/|watch\?v=)|youtu\.be/)([^"&?/\s]{11})%i', $video->video_url, $match)) {
                                            $youtubeId = $match[1];
                                        }
                                    @endphp
                                    @if($youtubeId)
                                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/mqdefault.jpg" alt="YouTube Video" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="youtube" class="w-12 h-12 text-rose-600"></i>
                                    @endif
                                @elseif($video->file_path)
                                    <video src="{{ $video->file_url }}" preload="metadata" class="w-full h-full object-cover video-preview" muted playsinline></video>
                                @else
                                    <i data-lucide="video" class="w-12 h-12 text-indigo-700"></i>
                                @endif
                                <div class="absolute inset-0 bg-stone-950/20 flex items-center justify-center">
                                    <i data-lucide="play" class="w-12 h-12 text-white opacity-90 group-hover:scale-110 transition-transform"></i>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-bold text-sm text-stone-900 leading-snug group-hover:text-amber-700 transition-colors">{{ $video->title }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

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

        const ytPlayer = document.getElementById('modal-youtube-player');
        const h5Player = document.getElementById('modal-html5-player');

        ytPlayer.src = '';
        h5Player.pause();
        h5Player.src = '';
    }

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
