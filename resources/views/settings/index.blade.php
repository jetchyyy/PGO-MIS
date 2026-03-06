@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">System Settings</p>
            <p class="text-blue-200 text-[11px]">Manage signatories, fund clusters, and other configurable system parameters</p>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Settings</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">

        {{-- Settings Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Signatories Card --}}
            <a href="{{ route('signatories.index') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Document Signatories</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $signatories->count() }}</p>
                            <p class="text-xs text-gray-500">configured signatories</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Manage the names, designations, and roles of officials who appear on printed government property documents (PAR, ICS, ITR, PTR, WMR, RRSEP).</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($signatories->take(3) as $sig)
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-semibold border border-gray-200 bg-gray-50 text-gray-600">{{ $sig->name }}</span>
                        @endforeach
                        @if($signatories->count() > 3)
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] text-gray-400">+{{ $signatories->count() - 3 }} more</span>
                        @endif
                    </div>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Manage Signatories
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Fund Clusters Card --}}
            <a href="{{ route('fund-clusters.index') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Fund Clusters</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $fundClusters->count() }}</p>
                            <p class="text-xs text-gray-500">fund clusters</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Manage funding source classifications for property transactions. Fund clusters appear in dropdowns when creating issuances, transfers, and disposals.</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($fundClusters as $fc)
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-semibold border border-emerald-200 bg-emerald-50 text-emerald-700">{{ $fc->code }} &mdash; {{ $fc->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Manage Fund Clusters
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Item Catalog Card --}}
            <a href="{{ route('items.index') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Item Catalog</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-violet-50 border border-violet-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $items->count() }}</p>
                            <p class="text-xs text-gray-500">items in catalog</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Master list of PPE, semi-expendable, and other property items. Items can be searched and auto-filled when creating transactions.</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($items->take(4) as $it)
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-semibold border border-violet-200 bg-violet-50 text-violet-700">{{ $it->name }}</span>
                        @endforeach
                        @if($items->count() > 4)
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] text-gray-400">+{{ $items->count() - 4 }} more</span>
                        @endif
                    </div>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Manage Items
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Profile / Account Card --}}
            <a href="{{ route('profile.edit') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">My Account</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Update your profile information, email address, and password.</p>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Edit Profile
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            @if(Auth::user()->hasRole(\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_SYSTEM_ADMIN))
            {{-- User Accounts Card --}}
            <a href="{{ route('users.index') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">User Accounts</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-sky-50 border border-sky-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5V9H2v11h5m10 0v-2a4 4 0 10-8 0v2m8 0H9m4-8a3 3 0 110-6 3 3 0 010 6z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">{{ $userCount }}</p>
                            <p class="text-xs text-gray-500">registered users</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Create new login accounts and assign application roles such as Property Staff, Accountable Officer, and Approving Official.</p>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Manage Accounts
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasRole(\App\Models\User::ROLE_SUPER_ADMIN))
            {{-- White Label Card --}}
            <a href="{{ route('white-label.edit') }}" class="group bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-[#1a2c5b]/30 transition overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">White Label</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-indigo-50 border border-indigo-200 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v4m0 0l-3-3m3 3l3-3m6 11a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h2m10 0h2a2 2 0 012 2v8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">Brand</p>
                            <p class="text-xs text-gray-500">text and media customization</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Customize website name, public text, logos, backgrounds, Open Graph image, and favicon from one screen.</p>
                </div>
                <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-100 text-xs font-semibold text-[#1a2c5b] group-hover:text-blue-700 flex items-center gap-1">
                    Manage Branding
                    <svg class="h-3 w-3 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @endif

        </div>

        {{-- System Info --}}
        <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">System Information</h2>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Application</p>
                        <p class="font-semibold text-gray-800">PGSO-SDN PMIS</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Framework</p>
                        <p class="font-semibold text-gray-800">Laravel {{ app()->version() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">PHP Version</p>
                        <p class="font-semibold text-gray-800">{{ phpversion() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Environment</p>
                        <p class="font-semibold text-gray-800">{{ app()->environment() }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
