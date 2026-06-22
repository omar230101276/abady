@extends('layouts.app')

@section('title', 'Albums & Galleries | Abady')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Title -->
    <div class="mb-12 border-b border-stone-200 pb-8">
        <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Works Directory</span>
        <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">ALBUMS GALLERY</h1>
    </div>

    @if($albums->isEmpty())
        <div class="bg-white border border-stone-200 rounded-xl p-16 text-center text-stone-400 text-sm shadow-sm">
            No albums available yet. Check back soon as new photography collections are added!
        </div>
    @else
        <!-- Split View Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            <!-- Sidebar Navigation -->
            <aside class="space-y-6 lg:sticky lg:top-24">
                <h3 class="font-syne text-xs uppercase tracking-wider text-stone-500 font-bold">Collections</h3>
                <div class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-x-visible pb-3 lg:pb-0 gap-2 border-b lg:border-b-0 border-stone-200">
                    <!-- 'Show All' Tab -->
                    <button onclick="filterPhotos('all')" id="tab-all" class="album-tab text-left px-4 py-3 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors bg-stone-950 text-white w-full">
                        All Collections
                    </button>
                    
                    @foreach($albums as $album)
                        <button onclick="filterPhotos('{{ $album->id }}')" id="tab-{{ $album->id }}" class="album-tab text-left px-4 py-3 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors text-stone-600 hover:bg-stone-100 hover:text-stone-950 w-full flex items-center justify-between">
                            <span>{{ $album->title }}</span>
                            <span class="text-xs px-2.5 py-0.5 bg-stone-100 border border-stone-200 text-stone-500 rounded-full font-medium ml-2">
                                {{ $album->photos->count() }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </aside>

            <!-- Main Photos Panel Grid -->
            <div class="lg:col-span-3">
                <!-- Albums Description -->
                <div class="mb-8 border-b border-stone-100 pb-6">
                    <!-- Description for 'All' -->
                    <div id="desc-all" class="album-desc space-y-2">
                        <p class="text-stone-550 text-sm leading-relaxed font-light">
                            Browse Abady's comprehensive photography records, spanning editorial campaigns, portraits, architectural lines, and landscape reportages.
                        </p>
                    </div>
                    @foreach($albums as $album)
                        <div id="desc-{{ $album->id }}" class="album-desc hidden space-y-2 animate-[fadeIn_0.3s_ease-out]">
                            <h2 class="font-syne font-bold text-xl text-stone-900">{{ $album->title }}</h2>
                            <p class="text-stone-550 text-sm leading-relaxed font-light">
                                {{ $album->description ?? 'Explore this photography collection.' }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- Photos Grid Container -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6" id="photos-grid">
                    @foreach($albums as $album)
                        @foreach($album->photos as $photo)
                            <div class="photo-card relative group cursor-pointer overflow-hidden bg-stone-100 aspect-square border border-stone-200 rounded-lg shadow-sm"
                                 data-album-id="{{ $album->id }}"
                                 data-image-src="{{ $photo->image_url }}"
                                 data-album-title="{{ $album->title }}"
                                 onclick="openLightbox(this)">
                                
                                <img src="{{ $photo->thumbnail_url }}" alt="Photo" class="w-full h-full object-cover transition-all duration-700 ease-out group-hover:scale-102" loading="lazy">
                                
                                <!-- Card Hover Overlay -->
                                <div class="absolute inset-0 bg-stone-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-350 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-stone-900 shadow">
                                        <i data-lucide="maximize-2" class="w-4 h-4"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Empty State for Photos -->
                <div id="empty-photos" class="hidden py-16 text-center text-stone-400 text-sm border border-dashed border-stone-200 rounded-xl bg-white">
                    This album does not contain any photos yet.
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Fullscreen Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 z-50 bg-white/98 backdrop-blur-md hidden flex-col items-center justify-center select-none" role="dialog" aria-modal="true">
    <!-- Close Header -->
    <div class="absolute top-0 inset-x-0 h-20 px-6 flex items-center justify-between border-b border-stone-150/60 bg-white">
        <div>
            <h4 id="lightbox-album-title" class="font-syne font-bold text-sm text-stone-900 tracking-wide uppercase">Album Title</h4>
            <span id="lightbox-counter" class="text-xs text-stone-400">Photo 1 of 12</span>
        </div>
        <button onclick="closeLightbox()" class="p-3 text-stone-600 hover:text-stone-950 hover:bg-stone-50 rounded-full transition-colors" aria-label="Close Lightbox">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Image Display Frame -->
    <div class="relative w-full h-[70vh] flex items-center justify-center px-4">
        <!-- Navigation Prev Button -->
        <button id="lightbox-prev-btn" onclick="prevImage()" class="absolute left-6 top-1/2 -translate-y-1/2 p-4 text-stone-600 hover:text-stone-950 hover:bg-stone-100/50 rounded-full transition-colors" aria-label="Previous Image">
            <i data-lucide="chevron-left" class="w-8 h-8"></i>
        </button>

        <!-- The Image -->
        <img id="lightbox-img" src="" alt="Expanded View" class="max-h-full max-w-full object-contain shadow-md rounded border border-stone-100 transition-transform duration-300">

        <!-- Navigation Next Button -->
        <button id="lightbox-next-btn" onclick="nextImage()" class="absolute right-6 top-1/2 -translate-y-1/2 p-4 text-stone-600 hover:text-stone-950 hover:bg-stone-100/50 rounded-full transition-colors" aria-label="Next Image">
            <i data-lucide="chevron-right" class="w-8 h-8"></i>
        </button>
    </div>

    <!-- Bottom Actions -->
    <div class="absolute bottom-6 flex items-center gap-6">
        <button onclick="zoomImage()" class="p-3 text-stone-500 hover:text-stone-950 hover:bg-stone-100 rounded-full transition-colors" title="Zoom In/Out">
            <i data-lucide="zoom-in" id="zoom-icon" class="w-5 h-5"></i>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let activeAlbum = 'all';
    let currentPhotos = [];
    let currentPhotoIndex = 0;
    let isZoomed = false;

    // Filter albums
    function filterPhotos(albumId) {
        activeAlbum = albumId;

        // Toggle Sidebar Active styling
        document.querySelectorAll('.album-tab').forEach(btn => {
            btn.className = 'album-tab text-left px-4 py-3 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors text-stone-600 hover:bg-stone-100 hover:text-stone-950 w-full flex items-center justify-between';
        });
        
        const activeBtn = document.getElementById(`tab-${albumId}`);
        if (activeBtn) {
            activeBtn.className = 'album-tab text-left px-4 py-3 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors bg-stone-950 text-white w-full flex items-center justify-between';
        }

        // Toggle Descriptions
        document.querySelectorAll('.album-desc').forEach(desc => {
            desc.classList.add('hidden');
        });
        const activeDesc = document.getElementById(`desc-${albumId}`);
        if (activeDesc) {
            activeDesc.classList.remove('hidden');
        }

        // Toggle Visibility of Photos
        let visibleCount = 0;
        document.querySelectorAll('.photo-card').forEach(card => {
            const cardAlbumId = card.getAttribute('data-album-id');
            if (albumId === 'all' || cardAlbumId === albumId) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Toggle Empty state
        const emptyDiv = document.getElementById('empty-photos');
        if (visibleCount === 0) {
            emptyDiv.classList.remove('hidden');
        } else {
            emptyDiv.classList.add('hidden');
        }
    }

    // Lightbox Functionality
    function openLightbox(element) {
        // Collect visible photos in current album view to set up slideshow index
        currentPhotos = Array.from(document.querySelectorAll('.photo-card')).filter(card => {
            return activeAlbum === 'all' || card.getAttribute('data-album-id') === activeAlbum;
        });

        currentPhotoIndex = currentPhotos.indexOf(element);
        updateLightbox();

        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden'; // Lock scrolling
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = 'auto'; // Unlock scrolling
        resetZoom();
    }

    function prevImage() {
        if (currentPhotos.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex - 1 + currentPhotos.length) % currentPhotos.length;
        updateLightbox();
        resetZoom();
    }

    function nextImage() {
        if (currentPhotos.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex + 1) % currentPhotos.length;
        updateLightbox();
        resetZoom();
    }

    function updateLightbox() {
        if (currentPhotos.length === 0) return;
        const currentCard = currentPhotos[currentPhotoIndex];
        
        const src = currentCard.getAttribute('data-image-src');
        const albumTitle = currentCard.getAttribute('data-album-title');

        const img = document.getElementById('lightbox-img');
        img.src = src;

        const titleHeader = document.getElementById('lightbox-album-title');
        titleHeader.textContent = albumTitle;

        const counter = document.getElementById('lightbox-counter');
        counter.textContent = `Photo ${currentPhotoIndex + 1} of ${currentPhotos.length}`;
        
        // Show/hide prev/next buttons based on count
        const prevBtn = document.getElementById('lightbox-prev-btn');
        const nextBtn = document.getElementById('lightbox-next-btn');
        if (currentPhotos.length <= 1) {
            prevBtn.classList.add('hidden');
            nextBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
            nextBtn.classList.remove('hidden');
        }
    }

    function zoomImage() {
        const img = document.getElementById('lightbox-img');
        const icon = document.getElementById('zoom-icon');
        isZoomed = !isZoomed;
        
        if (isZoomed) {
            img.style.transform = 'scale(1.4)';
            icon.setAttribute('data-lucide', 'zoom-out');
        } else {
            img.style.transform = 'scale(1)';
            icon.setAttribute('data-lucide', 'zoom-in');
        }
        lucide.createIcons();
    }

    function resetZoom() {
        const img = document.getElementById('lightbox-img');
        const icon = document.getElementById('zoom-icon');
        isZoomed = false;
        img.style.transform = 'scale(1)';
        icon.setAttribute('data-lucide', 'zoom-in');
        lucide.createIcons();
    }

    // Keyboard support for Lightbox
    window.addEventListener('keydown', (e) => {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    });
</script>
@endsection
