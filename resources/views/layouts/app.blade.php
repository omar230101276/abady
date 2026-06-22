<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Abady | Creative Photographer Portfolio')</title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="@yield('meta_description', 'Official portfolio of Abady. A showcase of professional photography, cinematic videos, client collaborations, and visual arts.')">
    <meta name="keywords" content="photography, portfolio, cinematographer, photographer, collaborations, gallery, albums">
    <meta name="author" content="Abady">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-syne {
            font-family: 'Syne', sans-serif;
        }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 antialiased selection:bg-stone-900 selection:text-white overflow-x-hidden min-h-screen flex flex-col">

    <!-- Floating Glassmorphic Header -->
    <header class="sticky top-0 z-40 w-full bg-white/70 backdrop-blur-md border-b border-stone-200/50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="font-syne text-2xl font-extrabold tracking-wider text-stone-950 uppercase flex items-center gap-2">
                Abady<span class="text-amber-600">.</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('home') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Home</a>
                <a href="{{ route('albums') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('albums') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Albums</a>
                <a href="{{ route('media') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('media') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Media</a>
                <a href="{{ route('collaborations') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('collaborations') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Collaborations</a>
                <a href="{{ route('contact') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('contact') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Contact</a>
                <a href="{{ route('bookings.index') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('bookings.index') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Book Session</a>
                <a href="{{ route('bookings.lookup.form') }}" class="font-medium text-sm transition-colors {{ Request::routeIs('bookings.lookup.*') || Request::routeIs('bookings.portal') ? 'text-amber-700 font-semibold' : 'text-stone-600 hover:text-stone-950' }}">Manage Booking</a>
            </nav>

            <!-- Actions (Removed login/dashboard shortcuts for security) -->
            <div class="hidden md:flex items-center gap-4">
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-toggle" class="md:hidden text-stone-900 p-2 hover:bg-stone-100 transition-colors" aria-label="Toggle Menu">
                <i data-lucide="menu" id="menu-icon" class="w-6 h-6"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="fixed inset-0 top-20 z-30 bg-white/95 backdrop-blur-lg border-b border-stone-200/80 hidden flex-col px-8 py-10 gap-6 transition-all duration-300 ease-in-out md:hidden">
        <a href="{{ route('home') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Home</a>
        <a href="{{ route('albums') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Albums</a>
        <a href="{{ route('media') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Media</a>
        <a href="{{ route('collaborations') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Collaborations</a>
        <a href="{{ route('contact') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Contact</a>
        <a href="{{ route('bookings.index') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Book Session</a>
        <a href="{{ route('bookings.lookup.form') }}" class="font-syne text-2xl font-bold text-stone-900 hover:text-amber-700 transition-colors">Manage Booking</a>
        <div class="h-px bg-stone-200 my-4"></div>
        <!-- Admin Entrance shortcuts removed -->
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Elegant Footer -->
    <footer class="bg-stone-900 text-stone-300 pt-16 pb-8 mt-20 border-t border-stone-850">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Col 1 -->
            <div>
                <a href="{{ route('home') }}" class="font-syne text-2xl font-bold tracking-widest text-white uppercase">
                    Abady<span class="text-amber-500">.</span>
                </a>
                <p class="mt-4 text-stone-400 text-sm leading-relaxed max-w-xs">
                    Capturing timeless frames, organic lights, and deep authentic human expressions through fine-art storytelling.
                </p>
            </div>
            <!-- Col 2 -->
            <div>
                <h3 class="font-syne text-xs uppercase tracking-wider text-white font-semibold mb-4">Quick Links</h3>
                <div class="flex flex-col gap-2 text-sm">
                    <a href="{{ route('albums') }}" class="text-stone-400 hover:text-white transition-colors">Explore Albums</a>
                    <a href="{{ route('media') }}" class="text-stone-400 hover:text-white transition-colors">Cinema & Videos</a>
                    <a href="{{ route('collaborations') }}" class="text-stone-400 hover:text-white transition-colors">Brand Collaborations</a>
                    <a href="{{ route('bookings.index') }}" class="text-stone-400 hover:text-white transition-colors">Book Session</a>
                    <a href="{{ route('bookings.lookup.form') }}" class="text-stone-400 hover:text-white transition-colors">Manage Booking</a>
                    <a href="{{ route('contact') }}" class="text-stone-400 hover:text-white transition-colors">Inquire & Booking</a>
                </div>
            </div>
            <!-- Col 3 -->
            <div>
                <h3 class="font-syne text-xs uppercase tracking-wider text-white font-semibold mb-4">Studio</h3>
                <p class="text-stone-400 text-sm leading-relaxed mb-4">
                    Cairo, Egypt & Worldwide Bookings<br>
                    <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'hello@abady.com') }}" class="text-stone-300 hover:text-white transition-colors">{{ \App\Models\Setting::get('contact_email', 'hello@abady.com') }}</a>
                </p>
                <!-- Social links (icons only) -->
                <div class="flex items-center gap-4">
                    <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors" aria-label="Instagram">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors" aria-label="YouTube">
                        <i data-lucide="youtube" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors" aria-label="Twitter/X">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-stone-400 hover:text-amber-500 transition-colors" aria-label="Vimeo">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-8 border-t border-stone-800 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-4">
            <p>&copy; {{ date('Y') }} Abady Photography. All rights reserved.</p>
            <p class="tracking-wide">Designed & Engineered with premium quality</p>
        </div>
    </footer>

    <!-- Initializations and Basic Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Mobile Menu Toggle logic
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        toggleBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
            
            // Switch Icon
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.setAttribute('data-lucide', 'menu');
            } else {
                menuIcon.setAttribute('data-lucide', 'x');
            }
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
