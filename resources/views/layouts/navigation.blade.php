<nav x-data="{ open: false }" class="border-b border-blue-200 bg-white/95 backdrop-blur">
    <div class="bg-blue-950 py-1.5 text-center text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-300">
        Provincial Government - PGSO Digital Property System
    </div>

    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-10" />
                <div class="hidden sm:block">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-900">Government Portal</p>
                    <p class="text-sm font-bold text-slate-800">PGSO Property</p>
                </div>
            </a>

            <div class="hidden items-center gap-1 sm:flex sm:ms-4">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M3 10.5 12 3l9 7.5V21H3V10.5Z"/><path stroke-width="1.8" d="M9 21v-6h6v6"/></svg>
                    <span>{{ __('Dashboard') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('issuance.index')" :active="request()->routeIs('issuance.*')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="1.8"/><path stroke-width="1.8" d="M8 8h8M8 12h8M8 16h5"/></svg>
                    <span>{{ __('Issuance') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('transfer.index')" :active="request()->routeIs('transfer.*')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M4 7h13m0 0-3-3m3 3-3 3M20 17H7m0 0 3-3m-3 3 3 3"/></svg>
                    <span>{{ __('Transfer') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('disposal.index')" :active="request()->routeIs('disposal.*')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 7h14M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>
                    <span>{{ __('Disposal') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('approvals.index')" :active="request()->routeIs('approvals.*')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="m5 12 4 4 10-10"/></svg>
                    <span>{{ __('Approvals') }}</span>
                </x-nav-link>
                <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 20V10m7 10V4m7 16v-7"/></svg>
                    <span>{{ __('Reports') }}</span>
                </x-nav-link>
            </div>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center rounded-md border border-blue-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-300 hover:text-blue-900 focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="ms-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-blue-50 hover:text-blue-900 focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': !open}" class="hidden border-t border-blue-100 bg-white sm:hidden">
        <div class="space-y-1 py-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('issuance.index')" :active="request()->routeIs('issuance.*')">{{ __('Issuance') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transfer.index')" :active="request()->routeIs('transfer.*')">{{ __('Transfer') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('disposal.index')" :active="request()->routeIs('disposal.*')">{{ __('Disposal') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('approvals.index')" :active="request()->routeIs('approvals.*')">{{ __('Approvals') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">{{ __('Reports') }}</x-responsive-nav-link>
        </div>

        <div class="border-t border-blue-100 py-3">
            <div class="px-4">
                <div class="text-base font-medium text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
