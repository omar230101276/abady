<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Abady Admin | @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons -->
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
<body class="bg-stone-100 text-stone-900 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white border-r border-stone-200 flex-shrink-0 flex flex-col justify-between">
        <div>
            <!-- Sidebar Header -->
            <div class="h-20 border-b border-stone-200 flex items-center px-6 justify-between">
                <span class="font-syne text-xl font-bold tracking-wider text-stone-950 uppercase">
                    Admin Panel
                </span>
                <i data-lucide="shield-check" class="w-5 h-5 text-amber-600"></i>
            </div>

            <!-- Navigation Links -->
            <nav class="p-6 flex flex-col gap-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.dashboard') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>
                <a href="{{ route('admin.albums.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.albums.*') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <i data-lucide="image" class="w-4 h-4"></i> Albums
                </a>
                <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.videos.*') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <i data-lucide="video" class="w-4 h-4"></i> Videos
                </a>
                <a href="{{ route('admin.collaborations.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.collaborations.*') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <i data-lucide="users" class="w-4 h-4"></i> Collaborations
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.contacts.*') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <span class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-4 h-4"></i> Messages
                    </span>
                    @php
                        $unreadMessagesCount = \App\Models\Contact::where('is_read', false)->count();
                    @endphp
                    @if($unreadMessagesCount > 0)
                        <span class="text-[10px] px-2 py-0.5 bg-amber-600 text-white font-bold rounded-full">
                            {{ $unreadMessagesCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.bookings.*') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <span class="flex items-center gap-3">
                        <i data-lucide="calendar" class="w-4 h-4"></i> Bookings
                    </span>
                    @php
                        $pendingBookingsCount = \App\Models\Booking::whereIn('status', ['pending', 'verification_pending'])->count();
                    @endphp
                    @if($pendingBookingsCount > 0)
                        <span class="text-[10px] px-2 py-0.5 bg-amber-600 text-white font-bold rounded-full">
                            {{ $pendingBookingsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-lg transition-colors {{ Request::routeIs('admin.profile') ? 'bg-stone-950 text-white' : 'text-stone-600 hover:bg-stone-100 hover:text-stone-950' }}">
                    <i data-lucide="user" class="w-4 h-4"></i> Profile Settings
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Actions -->
        <div class="p-6 border-t border-stone-200 flex flex-col gap-4">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-xs font-semibold text-stone-500 hover:text-stone-950 transition-colors">
                <i data-lucide="external-link" class="w-4 h-4"></i> Visit Public Site
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold uppercase tracking-wider text-rose-700 border border-rose-200 hover:bg-rose-50 rounded-lg transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Panel -->
    <main class="flex-grow flex flex-col min-w-0">
        <!-- Header -->
        <header class="h-20 bg-white border-b border-stone-200 flex items-center justify-between px-8">
            <h1 class="font-syne text-lg font-bold text-stone-900">@yield('title')</h1>
            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 group">
                <span class="text-sm font-medium text-stone-600 group-hover:text-stone-950 transition-colors">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-stone-200 border border-stone-300 group-hover:border-stone-400 group-hover:bg-stone-300 transition-colors flex items-center justify-center font-bold text-stone-700 text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </a>
        </header>

        <!-- Main Panel Body -->
        <div class="p-8 flex-grow">
            <!-- Toast notifications -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    @yield('scripts')
</body>
</html>
