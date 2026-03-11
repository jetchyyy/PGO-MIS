<nav x-data="{ open: false }" class="relative z-50 w-full">
    <style>
        .wl-nav-bg { background-color: {{ $whiteLabel['secondary_color'] }}; }
        .wl-nav-border { border-color: {{ $whiteLabel['primary_color'] }}66; }
        .wl-nav-hover:hover { background-color: {{ $whiteLabel['primary_color'] }}66; }
        .wl-nav-active { background-color: {{ $whiteLabel['primary_color'] }}; color: {{ $whiteLabel['button_text_color'] }}; }
    </style>
    {{-- Main navbar - AdminLTE style dark --}}
    <div class="wl-nav-bg shadow-md border-b wl-nav-border">
        <div class="mx-auto flex min-h-[56px] max-w-full items-center justify-between gap-3 px-4 py-2">

            {{-- Left: Brand --}}
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3 shrink-0">
                <img src="{{ $whiteLabel['logo_url'] }}" alt="Logo" class="h-8 w-8 object-contain">
                <div class="hidden min-w-0 sm:block">
                    <span class="block truncate text-sm font-bold leading-none text-white">{{ $whiteLabel['nav_title'] }}</span>
                    <span class="block text-[10px] text-gray-400 leading-tight">{{ $whiteLabel['nav_subtitle'] }}</span>
                </div>
            </a>

            {{-- Center: Nav links (desktop) --}}
            <div class="hidden md:flex items-center gap-0.5 ml-6">
                @php $user = auth()->user(); @endphp

                <a href="{{ route('dashboard') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M3 10.5 12 3l9 7.5V21H3V10.5Z"/><path stroke-width="1.8" d="M9 21v-6h6v6"/></svg>Dashboard
                </a>

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
                <a href="{{ route('issuance.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('issuance.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-width="1.8" d="M9 8h6M9 12h6M9 16h4"/></svg>
                    Issuance
                </a>
                <a href="{{ route('items.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('items.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M4 7h16v13H4z"/><path stroke-width="1.8" d="M9 7V4h6v3"/></svg>
                    Item Catalog
                </a>
                <a href="{{ route('transfer.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('transfer.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0-4-4m4 4-4 4m0 6H4m0 0 4-4m-4 4 4 4"/></svg>
                    Transfer
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_APPROVING_OFFICIAL))
                <a href="{{ route('returns.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('returns.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7z"/><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                    Returns
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('disposal.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('disposal.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2l1-12"/></svg>
                    Disposal
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
                <a href="{{ route('approvals.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('approvals.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="9" stroke-width="1.8"/></svg>
                    Approvals
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('reports.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 19V5m0 14h14"/><path stroke-width="1.8" d="M8 16V9m4 7V7m4 9v-4"/></svg>
                    Reports
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('settings.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('settings.*', 'signatories.*', 'fund-clusters.*', 'offices.*', 'accountable-officers.*', 'items.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.3c.4-1.8 3-1.8 3.4 0a1.8 1.8 0 0 0 2.6 1.1c1.6-.9 3.3.8 2.4 2.4a1.8 1.8 0 0 0 1.1 2.6c1.8.4 1.8 3 0 3.4a1.8 1.8 0 0 0-1.1 2.6c.9 1.6-.8 3.3-2.4 2.4a1.8 1.8 0 0 0-2.6 1.1c-.4 1.8-3 1.8-3.4 0a1.8 1.8 0 0 0-2.6-1.1c-1.6.9-3.3-.8-2.4-2.4a1.8 1.8 0 0 0-1.1-2.6c-1.8-.4-1.8-3 0-3.4a1.8 1.8 0 0 0 1.1-2.6c-.9-1.6.8-3.3 2.4-2.4.9.6 2.2.1 2.6-1.1Z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                    Settings
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN))
                <a href="{{ route('users.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('users.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/><path stroke-width="1.8" d="M4 20a8 8 0 0 1 16 0"/></svg>
                    Users
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN))
                <a href="{{ route('white-label.edit') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('white-label.*') ? 'wl-nav-active' : 'text-gray-300 hover:text-white wl-nav-hover' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 17v3h3l9-9-3-3-9 9Zm11-11 3 3m-9 9h3"/></svg>
                    White Label
                </a>
                @endif
            </div>

            {{-- Right: User dropdown --}}
            <div class="ml-auto hidden md:flex items-center gap-3">
                <div class="relative" x-data="{ dropOpen: false }">
                    <button @click="dropOpen = !dropOpen" class="flex items-center gap-2 text-gray-300 hover:text-white transition px-3 py-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="max-w-[12rem] truncate text-sm font-medium">{{ Auth::user()->name }}</span>
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="dropOpen" @click.outside="dropOpen = false"
                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-1 w-48 origin-top-right bg-white border border-gray-200 shadow-lg z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile toggle --}}
            <button @click="open = !open" class="md:hidden flex items-center justify-center p-2 text-gray-300 hover:text-white transition">
                <svg x-show="!open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak x-transition class="md:hidden wl-nav-bg border-b wl-nav-border">
        <div class="space-y-0.5 px-3 py-2">
            @php $user = auth()->user(); @endphp
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M3 10.5 12 3l9 7.5V21H3V10.5Z"/><path stroke-width="1.8" d="M9 21v-6h6v6"/></svg>
                Dashboard
            </a>
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
            <a href="{{ route('issuance.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path stroke-width="1.8" d="M9 8h6M9 12h6M9 16h4"/></svg>
                Issuance
            </a>
            <a href="{{ route('items.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M4 7h16v13H4z"/><path stroke-width="1.8" d="M9 7V4h6v3"/></svg>
                Item Catalog
            </a>
            <a href="{{ route('transfer.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0-4-4m4 4-4 4m0 6H4m0 0 4-4m-4 4 4 4"/></svg>
                Transfer
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_APPROVING_OFFICIAL))
            <a href="{{ route('returns.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7z"/><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                Returns
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('disposal.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0 1 12a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2l1-12"/></svg>
                Disposal
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
            <a href="{{ route('approvals.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="9" stroke-width="1.8"/></svg>
                Approvals
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('reports.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 19V5m0 14h14"/><path stroke-width="1.8" d="M8 16V9m4 7V7m4 9v-4"/></svg>
                Reports
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('settings.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M10.3 4.3c.4-1.8 3-1.8 3.4 0a1.8 1.8 0 0 0 2.6 1.1c1.6-.9 3.3.8 2.4 2.4a1.8 1.8 0 0 0 1.1 2.6c1.8.4 1.8 3 0 3.4a1.8 1.8 0 0 0-1.1 2.6c.9 1.6-.8 3.3-2.4 2.4a1.8 1.8 0 0 0-2.6 1.1c-.4 1.8-3 1.8-3.4 0a1.8 1.8 0 0 0-2.6-1.1c-1.6.9-3.3-.8-2.4-2.4a1.8 1.8 0 0 0-1.1-2.6c-1.8-.4-1.8-3 0-3.4a1.8 1.8 0 0 0 1.1-2.6c-.9-1.6.8-3.3 2.4-2.4.9.6 2.2.1 2.6-1.1Z"/><circle cx="12" cy="12" r="3" stroke-width="1.8"/></svg>
                Settings
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN))
            <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/><path stroke-width="1.8" d="M4 20a8 8 0 0 1 16 0"/></svg>
                Users
            </a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SUPER_ADMIN))
            <a href="{{ route('white-label.edit') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white wl-nav-hover">
                <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 17v3h3l9-9-3-3-9 9Zm11-11 3 3m-9 9h3"/></svg>
                White Label
            </a>
            @endif
        </div>
        <div class="border-t wl-nav-border px-4 py-3">
            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            <div class="mt-2 flex flex-wrap gap-3">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-300 hover:text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 text-sm text-red-400 hover:text-red-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
