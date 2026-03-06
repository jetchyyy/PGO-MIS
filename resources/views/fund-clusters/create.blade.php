@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Settings</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Add Fund Cluster</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
            <span>&rsaquo;</span>
            <a href="{{ route('fund-clusters.index') }}" class="hover:text-[#1a2c5b]">Fund Clusters</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Add</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 max-w-2xl">
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Fund Cluster Details</h2>
            </div>
            <form method="POST" action="{{ route('fund-clusters.store') }}" class="p-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g. FC-01"
                           class="w-full rounded border border-gray-300 text-sm px-3 py-2 uppercase">
                    @error('code') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Name / Description</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. General Fund"
                           class="w-full rounded border border-gray-300 text-sm px-3 py-2">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="rounded bg-[#1a2c5b] px-6 py-2 text-sm font-semibold text-white hover:bg-[#253d82] transition">Save Fund Cluster</button>
                    <a href="{{ route('fund-clusters.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
