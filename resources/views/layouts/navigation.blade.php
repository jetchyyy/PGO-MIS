<nav x-data="{ open: false }" class="relative z-50 w-full">
    {{-- Top government banner strip --}}
    <div class="bg-blue-950 py-1 text-center text-[10px] font-bold uppercase tracking-[0.25em] text-amber-400 border-b border-amber-400/20">
        Republic of the Philippines &nbsp;|&nbsp; Province of Surigao Del Norte
    </div>

    {{-- Main navigation bar --}}
    <div class="bg-gradient-to-r from-blue-950 via-blue-900 to-blue-950 shadow-xl border-b border-blue-800/50">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            {{-- Left: Logo + System name --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/10 p-1 shadow-inner ring-1 ring-white/20 transition group-hover:bg-white/20">
                    <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Province Seal" class="h-full w-full object-contain drop-shadow">
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-400/80">Province of Surigao Del Norte</p>
                    <p class="text-[11px] font-extrabold uppercase tracking-widest text-white leading-tight">Provincial General Services Office</p>
                    <p class="text-[10px] font-medium text-blue-300 leading-tight mt-0.5">Property Management Information System</p>
                </div>
            </a>

            {{-- Center: Navigation links (desktop) --}}
            <div class="hidden items-center gap-1 md:flex">
                @php $user = auth()->user(); @endphp

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('dashboard') ? 'bg-amber-400/20 text-amber-300 ring-1 ring-amber-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M3 10.5 12 3l9 7.5V21H3V10.5Z"/><path stroke-width="1.8" d="M9 21v-6h6v6"/></svg>
                    Home
                </a>

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
                <a href="{{ route('issuance.index') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('issuance.*') ? 'bg-blue-400/20 text-blue-200 ring-1 ring-blue-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="1.8"/><path stroke-width="1.8" d="M8 8h8M8 12h8M8 16h5"/></svg>
                    Issuance
                </a>

                <a href="{{ route('transfer.index') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('transfer.*') ? 'bg-emerald-400/20 text-emerald-200 ring-1 ring-emerald-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M4 7h13m0 0-3-3m3 3-3 3M20 17H7m0 0 3-3m-3 3 3 3"/></svg>
                    Transfer
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('disposal.index') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('disposal.*') ? 'bg-rose-400/20 text-rose-200 ring-1 ring-rose-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 7h14M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>
                    Disposal
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
                <a href="{{ route('approvals.index') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('approvals.*') ? 'bg-purple-400/20 text-purple-200 ring-1 ring-purple-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="m5 12 4 4 10-10"/></svg>
                    Approvals
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('reports.*') ? 'bg-violet-400/20 text-violet-200 ring-1 ring-violet-400/30' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 20V10m7 10V4m7 16v-7"/></svg>
                    Reports
                </a>
                @endif
            </div>

            {{-- Right: User info + logout --}}
            <div class="hidden md:flex items-center gap-3">
                <div class="flex flex-col items-end">
                    <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] font-medium uppercase tracking-widest text-amber-300">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                    </span>
                </div>

                <div class="h-8 w-px bg-blue-700"></div>

                {{-- Profile & Logout dropdown --}}
                <div class="relative" x-data="{ dropOpen: false }">
                    <button @click="dropOpen = !dropOpen" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                    <div x-show="dropOpen" @click.outside="dropOpen = false"
                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-blue-950 border border-blue-800 py-1 shadow-2xl ring-1 ring-black/20 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-blue-200 hover:bg-white/10 hover:text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <div class="my-1 border-t border-blue-800"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-rose-300 hover:bg-white/10 hover:text-rose-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile menu toggle --}}
            <button @click="open = !open" class="md:hidden flex items-center justify-center rounded-lg p-2 text-blue-200 hover:bg-white/10 hover:text-white transition">
                <svg x-show="!open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="md:hidden bg-blue-950 border-b border-blue-800 shadow-xl">
        <div class="space-y-1 px-4 py-3">
            @php $user = auth()->user(); @endphp
            <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Home</a>
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
            <a href="{{ route('issuance.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Issuance</a>
            <a href="{{ route('transfer.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Transfer</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('disposal.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Disposal</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
            <a href="{{ route('approvals.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Approvals</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('reports.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-blue-200 hover:bg-white/10 hover:text-white">Reports</a>
            @endif
        </div>
        <div class="border-t border-blue-800 px-4 py-3">
            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-amber-300 uppercase tracking-widest">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            <div class="mt-2 flex gap-3">
                <a href="{{ route('profile.edit') }}" class="text-sm text-blue-200 hover:text-white">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-rose-300 hover:text-rose-200">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
