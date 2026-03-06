<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $whiteLabel['app_name'] ?? config('app.name', 'PGSO-SDN PMIS') }}</title>

    <meta name="title" content="{{ $whiteLabel['meta_title'] }}">
    <meta name="description" content="{{ $whiteLabel['meta_description'] }}">
    <meta name="keywords" content="PGSO, Property MIS, Surigao del Norte, Provincial Government, Property Management, Inventory System">
    <meta name="author" content="Provincial Government of Surigao del Norte">
    <meta name="theme-color" content="#0d47a1">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $whiteLabel['meta_title'] }}">
    <meta property="og:description" content="{{ $whiteLabel['meta_description'] }}">
    <meta property="og:image" content="{{ $whiteLabel['og_image_url'] }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ $whiteLabel['app_name'] }}">
    <meta property="og:locale" content="en_PH">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $whiteLabel['meta_title'] }}">
    <meta name="twitter:description" content="{{ $whiteLabel['meta_description'] }}">
    <meta name="twitter:image" content="{{ $whiteLabel['og_image_url'] }}">

    <link rel="icon" type="image/x-icon" href="{{ $whiteLabel['favicon_url'] }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>[x-cloak]{display:none!important;}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#f4f6f9] text-gray-800 text-sm">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 w-full max-w-7xl mx-auto px-4 py-5 sm:px-6 lg:px-8">
            @if(session('status') && !str_contains((string) session('status'), 'profile-'))
                <div class="mb-4 border-l-4 border-green-500 bg-green-50 p-3 text-sm text-green-700">
                    <i class="mr-1">&#10003;</i> {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 border-l-4 border-red-500 bg-red-50 p-3 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-0.5">
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

        <footer class="bg-white border-t border-gray-200 py-3 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} <strong>{{ $whiteLabel['app_name'] }}</strong> - {{ $whiteLabel['footer_text'] }}.
        </footer>
    </div>
</body>
</html>
