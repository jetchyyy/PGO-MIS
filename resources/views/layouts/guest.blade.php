<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PGSO-SDN PMIS') }}</title>

    {{-- Primary Meta Tags --}}
    <meta name="title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta name="description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta name="theme-color" content="#0d47a1">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta property="og:description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="PGSO Property MIS">
    <meta property="og:locale" content="en_PH">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta name="twitter:description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-['DM_Sans'] text-slate-900 antialiased bg-slate-900">
    <div class="relative min-h-screen w-full flex flex-col items-center justify-center p-4">
        
        {{-- Background Image & Overlay --}}
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/sdncapitollongshot.jpg') }}" alt="Surigao del Norte Capitol" class="h-full w-full object-cover object-center opacity-30">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/90 via-slate-900/75 to-slate-900/90"></div>
        </div>

        {{-- Content Wrapper --}}
        <div class="relative z-10 w-full max-w-md">
            
            {{-- Branding Header --}}
            <div class="mb-8 text-center text-white">
                <a href="/" class="inline-flex flex-col items-center gap-4 group">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white/10 p-2.5 ring-2 ring-white/20 shadow-2xl transition-transform duration-300 group-hover:scale-105 group-hover:bg-white/15">
                        <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Logo" class="h-full w-full object-contain drop-shadow-md">
                    </div>
                    <div>
                        <span class="block text-xs font-medium tracking-[0.25em] text-white/70 uppercase drop-shadow-sm">Provincial General Services Office</span>
                        <span class="block text-2xl font-semibold tracking-wide text-white mt-1 drop-shadow-md">Property Management System</span>
                    </div>
                </a>
            </div>

            {{-- Glassmorphism Card --}}
            <div class="w-full rounded-3xl border border-white/20 bg-white/5 px-8 py-8 shadow-2xl backdrop-blur-xl">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-center text-xs font-bold tracking-widest uppercase text-white/50">
                &copy; {{ date('Y') }} Provincial Government of Surigao Del Norte
            </p>
        </div>
    </div>
</body>
</html>
