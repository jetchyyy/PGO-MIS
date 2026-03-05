<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PGSO-SDN PMIS</title>

    {{-- Primary Meta Tags --}}
    <meta name="title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta name="description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta name="keywords" content="PGSO, Property MIS, Surigao del Norte, Provincial Government, Property Management, Inventory System">
    <meta name="author" content="Provincial Government of Surigao del Norte">
    <meta name="theme-color" content="#0d47a1">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta property="og:description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="PGSO Property MIS">
    <meta property="og:locale" content="en_PH">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="PGSO Property MIS — Provincial Government of Surigao del Norte">
    <meta name="twitter:description" content="Property Management & Inventory System of the Provincial General Services Office (PGSO), Provincial Government of Surigao del Norte.">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Montserrat'] text-slate-900 antialiased bg-slate-900 selection:bg-white/30 selection:text-white overflow-x-hidden">
    
    {{-- Fixed Background --}}
    <div class="fixed inset-0 z-0 h-full w-full">
        <img src="{{ asset('images/sdncapitollongshot.jpg') }}" alt="Surigao del Norte Capitol" class="h-full w-full object-cover object-center scale-105 opacity-40 blur-[2px]">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-slate-900/80 to-slate-950/90"></div>
    </div>

    {{-- Top Navigation --}}
    <nav class="relative z-50 w-full border-b border-white/10 bg-slate-900/40 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 p-1 ring-1 ring-white/20">
                    <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div class="hidden sm:block">
                    <span class="block text-[10px] font-bold tracking-[0.2em] text-amber-400 uppercase">Provincial Government</span>
                    <span class="block text-sm font-black text-white tracking-wide">Surigao Del Norte</span>
                </div>
            </div>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center gap-2 overflow-hidden rounded-full border border-white/30 bg-white/10 px-6 py-2 text-sm font-bold tracking-wide text-white transition hover:bg-white/20">
                        <span>Go to Dashboard</span>
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group relative inline-flex items-center gap-2 overflow-hidden rounded-full border border-white/30 bg-white/10 px-6 py-2 text-sm font-bold tracking-wide text-white transition hover:bg-white/20">
                        <span>Sign In Securely</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="relative z-10 mx-auto max-w-7xl px-6 pt-16 pb-24 lg:pt-24 flex flex-col items-center text-center">
        
        {{-- Hero Section --}}
        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-white/10 p-4 ring-2 ring-white/20 shadow-2xl backdrop-blur-sm mb-8 animate-[pulse_5s_ease-in-out_infinite]">
            <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Provincial Seal" class="h-full w-full object-contain drop-shadow-xl">
        </div>
        
        <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-1.5 mb-6 backdrop-blur-md">
            <span class="flex h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
            <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-300">Official Government Portal</span>
        </div>

        <h1 class="mx-auto max-w-4xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl drop-shadow-lg">
            Provincial General Services Office
        </h1>
        <h2 class="mt-4 text-2xl font-bold tracking-wide text-white/80 sm:text-3xl lg:text-4xl drop-shadow-md">
            Property Management System
        </h2>
        
        <p class="mx-auto mt-6 max-w-2xl text-base font-normal leading-relaxed text-white/70 sm:text-lg">
            A centralized digital platform for managing, tracking, and auditing government properties, equipment, and issuances across all offices of Surigao Del Norte.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-8 py-4 text-base font-bold tracking-wide text-white shadow-xl backdrop-blur-md transition hover:-translate-y-1 hover:bg-white/20">
                    <svg class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Proceed to Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-8 py-4 text-base font-bold tracking-wide text-white shadow-xl backdrop-blur-md transition hover:-translate-y-1 hover:bg-white/20">
                    <svg class="h-6 w-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Login to Portal</span>
                </a>
            @endauth
        </div>

        {{-- Feature Cards --}}
        <div class="mt-24 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full text-center">
            
            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 flex flex-col items-center backdrop-blur-xl transition hover:bg-white/10 hover:border-white/30 shadow-xl group">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 mb-5 group-hover:scale-110 group-hover:bg-white/20 transition-all">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Issuance Tracking</h3>
                <p class="text-sm font-normal text-white/70 leading-relaxed">Manage and track Property Acknowledgement Receipts (PAR) and Inventory Custodian Slips (ICS).</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 flex flex-col items-center backdrop-blur-xl transition hover:bg-white/10 hover:border-white/30 shadow-xl group">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 mb-5 group-hover:scale-110 group-hover:bg-white/20 transition-all">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Property Transfers</h3>
                <p class="text-sm font-normal text-white/70 leading-relaxed">Streamline Property Transfer Reports (PTR) securely between different offices and officers.</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 flex flex-col items-center backdrop-blur-xl transition hover:bg-white/10 hover:border-white/30 shadow-xl group">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 mb-5 group-hover:scale-110 group-hover:bg-white/20 transition-all">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Disposal Logging</h3>
                <p class="text-sm font-normal text-white/70 leading-relaxed">Record Inspection and Inventory Reports of Unserviceable Properties (IIRUP) compliance.</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8 flex flex-col items-center backdrop-blur-xl transition hover:bg-white/10 hover:border-white/30 shadow-xl group">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 mb-5 group-hover:scale-110 group-hover:bg-white/20 transition-all">
                    <svg class="h-7 w-7 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Audit & Reporting</h3>
                <p class="text-sm font-normal text-white/70 leading-relaxed">Generate printable Physical Count Reports, RegSPI, and full system activity audit logs instantly.</p>
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 w-full flex flex-col items-center justify-center gap-2 border-t border-white/5 bg-slate-950/80 py-8 text-center backdrop-blur-md">
        <p class="text-[11px] font-bold tracking-widest text-white/60 uppercase">
            &copy; {{ date('Y') }} Provincial Government of Surigao Del Norte &mdash; PGSO Property Management System
        </p>
        <p class="text-[10px] font-medium tracking-widest text-white/40 uppercase">
            This system is built by New Zenith Datacom OPC (D.A.G, rocs, JM, dnkn - iykyk 😉)
        </p>
    </footer>

</body>
</html>
