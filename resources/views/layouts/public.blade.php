<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('messages.app_name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=ibm-plex-sans-arabic:400,500,600,700|inter:400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = stored ? stored === 'dark' : prefersDark;
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    <style>
        :root { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        html[lang="ar"] { font-family: 'IBM Plex Sans Arabic', ui-sans-serif, system-ui, sans-serif; }
        .leaflet-container { background: #18181b; }
        html:not(.dark) .leaflet-container { background: #f4f4f5; }
        .liqahi-marker, .liqahi-user-marker { background: transparent !important; border: 0 !important; }
        .liqahi-locate-btn {
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 9999px;
            background: white; color: rgb(63 63 70);
            box-shadow: 0 4px 10px rgba(0,0,0,.2);
            border: 0; cursor: pointer;
            margin: 0 16px 16px 0 !important;
        }
        html[dir="rtl"] .liqahi-locate-btn { margin: 0 0 16px 16px !important; }
        .liqahi-locate-btn:hover { color: #d97706; background: rgb(244 244 245); }
        html.dark .liqahi-locate-btn { background: rgb(39 39 42); color: rgb(228 228 231); }
        html.dark .liqahi-locate-btn:hover { color: #fbbf24; background: rgb(63 63 70); }
        .liqahi-scroll { scrollbar-width: thin; scrollbar-color: rgb(212 212 216) transparent; }
        html.dark .liqahi-scroll { scrollbar-color: rgb(63 63 70) transparent; }
        .liqahi-scroll::-webkit-scrollbar { width: 8px; height: 8px; }
        .liqahi-scroll::-webkit-scrollbar-track { background: transparent; }
        .liqahi-scroll::-webkit-scrollbar-thumb { background: rgb(212 212 216); border-radius: 9999px; }
        html.dark .liqahi-scroll::-webkit-scrollbar-thumb { background: rgb(63 63 70); }
        .liqahi-scroll::-webkit-scrollbar-thumb:hover { background: rgb(161 161 170); }
        html.dark .liqahi-scroll::-webkit-scrollbar-thumb:hover { background: rgb(82 82 91); }
    </style>
</head>
<body class="flex min-h-full flex-col bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <main class="flex-1">{{ $slot }}</main>

    @livewireScripts
</body>
</html>
