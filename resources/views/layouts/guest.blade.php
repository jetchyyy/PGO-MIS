<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PGSO Property') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased">
    <div class="min-h-screen bg-gradient-to-b from-blue-950 via-blue-900 to-blue-800 px-4 py-8">
        <div class="mx-auto max-w-md">
            <div class="mb-6 text-center text-white">
                <a href="/" class="inline-flex flex-col items-center gap-3">
                    <x-application-logo class="h-20 w-20" />
                    <span class="text-xs font-semibold tracking-[0.22em] text-amber-300">REPUBLIC OF THE PHILIPPINES</span>
                    <span class="text-lg font-bold tracking-wide">PGSO Property Management System</span>
                </a>
            </div>

            <div class="w-full overflow-hidden rounded-2xl border border-blue-200/30 bg-white/95 px-6 py-6 shadow-2xl backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
