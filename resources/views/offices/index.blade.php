@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Settings</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Offices</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('settings.index') }}" class="hover:text-[#1a2c5b]">Settings</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Offices</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Add Office</h2>
            </div>
            <form method="POST" action="{{ route('offices.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Office Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm uppercase" required>
                </div>
                <button type="submit" class="rounded bg-[#1a2c5b] px-5 py-2 text-sm font-semibold text-white hover:bg-[#253d82]">Save Office</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Office List</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Name</th>
                            <th class="px-4 py-2 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Code</th>
                            <th class="px-4 py-2 text-right text-[11px] font-bold uppercase tracking-widest text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offices as $office)
                        <tr class="border-b border-gray-100">
                            <td class="px-4 py-2.5 font-medium text-gray-800">{{ $office->name }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $office->code }}</td>
                            <td class="px-4 py-2.5 text-right">
                                <form method="POST" action="{{ route('offices.destroy', $office) }}" onsubmit="return confirm('Delete this office?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No offices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
