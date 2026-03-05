<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PGSO Property') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important;}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['DM_Sans'] antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="max-w-7xl mx-auto px-4 py-6">
            @if(session('status') && !str_contains((string) session('status'), 'profile-'))
                <div class="mb-4 rounded border border-emerald-200 bg-emerald-50 p-3 text-sm">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @isset($slot)
                {{ $slot }}
            @endisset

            @yield('content')
        </main>
    </div>
</body>
</html>
