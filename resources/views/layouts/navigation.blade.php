<nav x-data="{ open: false }" class="relative z-50 w-full">
    {{-- Main navbar - AdminLTE style dark --}}
    <div class="bg-[#343a40] shadow-md border-b border-[#4b545c]">
        <div class="mx-auto flex h-[50px] max-w-full items-center justify-between px-4">

            {{-- Left: Brand --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/surigaodelnorte.png') }}" alt="Logo" class="h-8 w-8 object-contain">
                <div class="hidden sm:block">
                    <span class="text-sm font-bold text-white leading-none">PGSO-PMIS</span>
                    <span class="block text-[10px] text-gray-400 leading-tight">Surigao Del Norte</span>
                </div>
            </a>

            {{-- Center: Nav links (desktop) --}}
            <div class="hidden md:flex items-center gap-0.5 ml-6">
                @php $user = auth()->user(); @endphp

                <a href="{{ route('dashboard') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    <svg class="inline h-4 w-4 mr-1 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M3 10.5 12 3l9 7.5V21H3V10.5Z"/><path stroke-width="1.8" d="M9 21v-6h6v6"/></svg>Dashboard
                </a>

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
                <a href="{{ route('issuance.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('issuance.*') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    Issuance
                </a>
                <a href="{{ route('transfer.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('transfer.*') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    Transfer
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('disposal.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('disposal.*') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    Disposal
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
                <a href="{{ route('approvals.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('approvals.*') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    Approvals
                </a>
                @endif

                @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
                <a href="{{ route('reports.index') }}"
                   class="px-3 py-2 text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'text-white bg-[#007bff]' : 'text-gray-300 hover:text-white hover:bg-[#495057]' }}">
                    Reports
                </a>
                @endif
            </div>

            {{-- Right: User dropdown --}}
            <div class="hidden md:flex items-center gap-3 ml-auto">
                <div class="relative" x-data="{ dropOpen: false }">
                    <button @click="dropOpen = !dropOpen" class="flex items-center gap-2 text-gray-300 hover:text-white transition px-3 py-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
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
    <div x-show="open" x-cloak x-transition class="md:hidden bg-[#343a40] border-b border-[#4b545c]">
        <div class="px-3 py-2 space-y-0.5">
            @php $user = auth()->user(); @endphp
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Dashboard</a>
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF, \App\Models\User::ROLE_ACCOUNTABLE_OFFICER))
            <a href="{{ route('issuance.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Issuance</a>
            <a href="{{ route('transfer.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Transfer</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('disposal.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Disposal</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_APPROVING_OFFICIAL))
            <a href="{{ route('approvals.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Approvals</a>
            @endif
            @if($user->hasRole(\App\Models\User::ROLE_SYSTEM_ADMIN, \App\Models\User::ROLE_AUDIT_VIEWER, \App\Models\User::ROLE_PROPERTY_STAFF))
            <a href="{{ route('reports.index') }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-[#495057]">Reports</a>
            @endif
        </div>
        <div class="border-t border-[#4b545c] px-4 py-3">
            <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
            <div class="mt-2 flex gap-4">
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-300 hover:text-white">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-red-400 hover:text-red-300">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
