@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Settings</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Fund Clusters</p>
            <p class="text-blue-200 text-[11px]">Manage fund clusters used in issuance, transfer, and disposal transactions</p>
        </div>
    </div>

    {{-- Breadcrumb & Actions --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Fund Clusters</span>
            </div>
            <a href="{{ route('fund-clusters.create') }}"
               class="inline-flex items-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82] transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Fund Cluster
            </a>
        </div>
    </div>

    @if(session('status'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 font-medium">{{ session('status') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700 font-medium">{{ session('error') }}</div>
    </div>
    @endif

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">

        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Registered Fund Clusters</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Code</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Name / Description</th>
                            <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fundClusters as $i => $fc)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 {{ $i % 2 ? 'bg-gray-50/50' : '' }}">
                            <td class="px-4 py-2.5">
                                <span class="inline-block rounded px-2 py-0.5 text-xs font-bold uppercase border border-blue-200 bg-blue-50 text-blue-700">{{ $fc->code }}</span>
                            </td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800">{{ $fc->name }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('fund-clusters.edit', $fc) }}" class="text-[#1a2c5b] hover:underline text-xs font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('fund-clusters.destroy', $fc) }}" onsubmit="return confirm('Delete this fund cluster?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline text-xs font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">No fund clusters configured yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 bg-white border border-gray-200 rounded shadow-sm p-5">
            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-600 mb-3">About Fund Clusters</h3>
            <div class="text-sm text-gray-600 space-y-1.5">
                <p>Fund clusters categorize funding sources for property transactions (e.g., General Fund, Special Education Fund, Trust Fund).</p>
                <p>They appear on printed ICS, PAR, ITR, PTR, RRSEP, and WMR documents and are selectable when creating issuances, transfers, and disposals.</p>
            </div>
        </div>

    </div>
</div>
@endsection
