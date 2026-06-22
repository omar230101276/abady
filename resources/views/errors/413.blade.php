<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payload Too Large | Abady</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 min-h-screen flex items-center justify-center px-6 selection:bg-stone-950 selection:text-white">
    <div class="max-w-md w-full bg-white border border-stone-200 p-8 rounded-2xl shadow-sm text-center space-y-6">
        <div class="w-16 h-16 bg-amber-50 text-amber-700 border border-amber-100 rounded-full flex items-center justify-center mx-auto shadow-sm">
            <i data-lucide="hard-drive-upload" class="w-8 h-8"></i>
        </div>
        
        <div class="space-y-2">
            <h1 class="font-syne text-2xl font-extrabold text-stone-950 uppercase tracking-wide">Payload Too Large</h1>
            <p class="text-xs text-stone-400 font-mono">HTTP Status 413</p>
        </div>

        <p class="text-stone-600 text-sm leading-relaxed font-light">
            The file you uploaded is too large and exceeds the maximum post limit allowed by the server's configuration.
        </p>

        <div class="p-4 bg-stone-50 rounded-lg text-left text-xs space-y-2 text-stone-750 border border-stone-200">
            <p class="font-bold text-stone-950 flex items-center gap-1.5">
                <i data-lucide="info" class="w-4 h-4 text-stone-500"></i> How to solve this:
            </p>
            <ol class="list-decimal pl-4 space-y-1.5 font-medium text-stone-600">
                <li>Open your configuration file: <br><span class="font-mono bg-white px-1.5 py-0.5 border border-stone-200 rounded block mt-1 overflow-x-auto text-[10px]">O:\System\Xampp\php\php.ini</span></li>
                <li class="mt-2">Increase <span class="font-mono bg-white px-1.5 py-0.5 border border-stone-200 rounded">upload_max_filesize</span> (e.g. to <span class="font-mono">100M</span> or <span class="font-mono">250M</span>)</li>
                <li>Increase <span class="font-mono bg-white px-1.5 py-0.5 border border-stone-200 rounded">post_max_size</span> (e.g. to <span class="font-mono">100M</span> or <span class="font-mono">250M</span>)</li>
                <li>Restart Apache inside your XAMPP Control Panel.</li>
            </ol>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row gap-3">
            <button onclick="window.history.back()" class="flex-1 py-3 px-4 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Go Back
            </button>
            <a href="{{ route('home') }}" class="flex-1 py-3 px-4 border border-stone-200 hover:bg-stone-50 text-stone-700 font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i> Return Home
            </a>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
