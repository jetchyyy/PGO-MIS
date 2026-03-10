<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $whiteLabel['app_name'] }}</title>

    <meta name="title" content="{{ $whiteLabel['meta_title'] }}">
    <meta name="description" content="{{ $whiteLabel['meta_description'] }}">
    <meta name="keywords" content="PGSO, Property MIS, Surigao del Norte, Provincial Government, Property Management, Inventory System">
    <meta name="author" content="Provincial Government of Surigao del Norte">
    <meta name="theme-color" content="{{ $whiteLabel['primary_color'] }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $whiteLabel['meta_title'] }}">
    <meta property="og:description" content="{{ $whiteLabel['meta_description'] }}">
    <meta property="og:image" content="{{ $whiteLabel['og_image_url'] }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ $whiteLabel['app_name'] }}">
    <meta property="og:locale" content="en_PH">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="{{ $whiteLabel['meta_title'] }}">
    <meta name="twitter:description" content="{{ $whiteLabel['meta_description'] }}">
    <meta name="twitter:image" content="{{ $whiteLabel['og_image_url'] }}">

    <link rel="icon" type="image/x-icon" href="{{ $whiteLabel['favicon_url'] }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Montserrat'] text-slate-900 antialiased bg-slate-900 selection:bg-white/30 selection:text-white overflow-x-hidden">
    <div class="fixed inset-0 z-0 h-full w-full">
        <img src="{{ $whiteLabel['welcome_bg_url'] }}" alt="Background" class="h-full w-full object-cover object-center scale-105 opacity-40 blur-[2px]">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/80 to-slate-950/90"></div>
    </div>

    <nav class="relative z-50 w-full border-b border-white/10 bg-slate-900/40 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 p-1 ring-1 ring-white/20 sm:h-11 sm:w-11">
                    <img src="{{ $whiteLabel['logo_url'] }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div class="min-w-0">
                    <span class="block text-[10px] font-bold tracking-[0.2em] uppercase" style="color: {{ $whiteLabel['accent_color'] }}">{{ $whiteLabel['nav_subtitle'] }}</span>
                    <span class="block text-sm font-black tracking-wide text-white sm:text-base">{{ $whiteLabel['nav_title'] }}</span>
                </div>
            </div>
            <div class="w-full md:w-auto">
                @auth
                    <a href="{{ url('/dashboard') }}" class="group relative inline-flex w-full items-center justify-center gap-2 overflow-hidden rounded-full border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold tracking-wide text-white transition hover:bg-white/20 md:w-auto">
                        <span>Go to Dashboard</span>
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group relative inline-flex w-full items-center justify-center gap-2 overflow-hidden rounded-full border border-white/30 bg-white/10 px-5 py-2.5 text-sm font-bold tracking-wide text-white transition hover:bg-white/20 md:w-auto">
                        <span>Sign In Securely</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="relative z-10 mx-auto flex max-w-7xl flex-col items-center px-4 pb-20 pt-12 text-center sm:px-6 sm:pt-16 lg:pt-24">
        <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white/10 p-3 ring-2 ring-white/20 shadow-2xl backdrop-blur-sm animate-[pulse_5s_ease-in-out_infinite] sm:mb-8 sm:h-28 sm:w-28 lg:h-32 lg:w-32 lg:p-4">
            <img src="{{ $whiteLabel['logo_url'] }}" alt="Seal" class="h-full w-full object-contain drop-shadow-xl">
        </div>

        <div class="mb-5 inline-flex max-w-full items-center gap-2 rounded-full border px-3 py-1.5 backdrop-blur-md sm:mb-6 sm:px-4" style="border-color: {{ $whiteLabel['accent_color'] }}66; background-color: {{ $whiteLabel['accent_color'] }}1a;">
            <span class="flex h-2 w-2 shrink-0 rounded-full animate-ping" style="background-color: {{ $whiteLabel['accent_color'] }}"></span>
            <span class="truncate text-[10px] font-bold uppercase tracking-[0.18em] sm:text-[11px]" style="color: {{ $whiteLabel['accent_color'] }}">{{ $whiteLabel['welcome_badge'] }}</span>
        </div>

        <h1 class="mx-auto max-w-4xl text-3xl font-black tracking-tight text-white drop-shadow-lg sm:text-5xl lg:text-6xl">
            {{ $whiteLabel['welcome_title'] }}
        </h1>
        <h2 class="mt-3 text-xl font-bold tracking-wide text-white/80 drop-shadow-md sm:mt-4 sm:text-3xl lg:text-4xl">
            {{ $whiteLabel['welcome_subtitle'] }}
        </h2>

        <p class="mx-auto mt-5 max-w-2xl px-1 text-sm font-normal leading-relaxed text-white/70 sm:mt-6 sm:text-lg">
            {{ $whiteLabel['welcome_description'] }}
        </p>

        <div class="mt-8 flex w-full max-w-md flex-col gap-3 sm:mt-10 sm:max-w-none sm:flex-row sm:justify-center sm:gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex min-h-14 w-full items-center justify-center gap-3 rounded-2xl border border-white/20 px-6 py-4 text-base font-bold tracking-wide shadow-xl backdrop-blur-md transition hover:-translate-y-1 sm:w-auto sm:px-8" style="background-color: {{ $whiteLabel['button_color'] }}; color: {{ $whiteLabel['button_text_color'] }};">
                    <svg class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Proceed to Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex min-h-14 w-full items-center justify-center gap-3 rounded-2xl border border-white/20 px-6 py-4 text-base font-bold tracking-wide shadow-xl backdrop-blur-md transition hover:-translate-y-1 sm:w-auto sm:px-8" style="background-color: {{ $whiteLabel['button_color'] }}; color: {{ $whiteLabel['button_text_color'] }};">
                    <svg class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Login to Portal</span>
                </a>
            @endauth
        </div>

        <div class="mt-14 grid w-full grid-cols-1 gap-4 text-center sm:mt-16 sm:gap-5 md:grid-cols-2 xl:mt-24 xl:grid-cols-4 xl:gap-6">
            <div class="group flex h-full flex-col items-center rounded-2xl border border-white/10 bg-white/5 p-5 shadow-xl backdrop-blur-xl transition hover:border-white/30 hover:bg-white/10 sm:p-6 lg:p-8">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20 sm:mb-5 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                </div>
                <h3 class="mb-2 text-base font-bold text-white sm:text-lg">Issuance Tracking</h3>
                <p class="text-sm font-normal leading-relaxed text-white/70">Manage and track Property Acknowledgement Receipts (PAR) and Inventory Custodian Slips (ICS).</p>
            </div>

            <div class="group flex h-full flex-col items-center rounded-2xl border border-white/10 bg-white/5 p-5 shadow-xl backdrop-blur-xl transition hover:border-white/30 hover:bg-white/10 sm:p-6 lg:p-8">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20 sm:mb-5 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="mb-2 text-base font-bold text-white sm:text-lg">Property Transfers</h3>
                <p class="text-sm font-normal leading-relaxed text-white/70">Streamline Property Transfer Reports (PTR) securely between different offices and officers.</p>
            </div>

            <div class="group flex h-full flex-col items-center rounded-2xl border border-white/10 bg-white/5 p-5 shadow-xl backdrop-blur-xl transition hover:border-white/30 hover:bg-white/10 sm:p-6 lg:p-8">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20 sm:mb-5 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="mb-2 text-base font-bold text-white sm:text-lg">Disposal Logging</h3>
                <p class="text-sm font-normal leading-relaxed text-white/70">Record Inspection and Inventory Reports of Unserviceable Properties (IIRUP) compliance.</p>
            </div>

            <div class="group flex h-full flex-col items-center rounded-2xl border border-white/10 bg-white/5 p-5 shadow-xl backdrop-blur-xl transition hover:border-white/30 hover:bg-white/10 sm:p-6 lg:p-8">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition-all group-hover:scale-110 group-hover:bg-white/20 sm:mb-5 sm:h-14 sm:w-14">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="mb-2 text-base font-bold text-white sm:text-lg">Audit and Reporting</h3>
                <p class="text-sm font-normal leading-relaxed text-white/70">Generate printable Physical Count Reports, RegSPI, and system activity audit logs instantly.</p>
            </div>
        </div>
    </main>

    <footer class="relative z-10 flex w-full flex-col items-center justify-center gap-2 border-t border-white/5 px-4 py-6 text-center backdrop-blur-md sm:px-6 sm:py-8" style="background-color: {{ $whiteLabel['secondary_color'] }}cc;">
        <p class="text-[10px] font-bold tracking-[0.24em] text-white/60 uppercase sm:text-[11px] sm:tracking-widest">
            &copy; {{ date('Y') }} {{ $whiteLabel['footer_text'] }}
        </p>
        <p class="text-[9px] font-medium tracking-[0.22em] text-white/40 uppercase sm:text-[10px] sm:tracking-widest">
            {{ $whiteLabel['footer_subtext'] }}
        </p>
    </footer>
</body>
</html>
