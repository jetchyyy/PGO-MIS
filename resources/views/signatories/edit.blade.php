@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Settings</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Edit Signatory</p>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('signatories.index') }}" class="hover:text-[#1a2c5b]">Signatories</a>
            <span>&rsaquo;</span>
            <span class="text-[#1a2c5b] font-semibold">Edit</span>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 max-w-2xl">
        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Signatory Details</h2>
            </div>
            <form method="POST" action="{{ route('signatories.update', $signatory) }}" class="p-5 space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Role</label>
                    <select name="role_key" required class="w-full rounded border border-gray-300 text-sm px-3 py-2">
                        @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role_key', $signatory->role_key) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role_key') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $signatory->name) }}" required
                           class="w-full rounded border border-gray-300 text-sm px-3 py-2 uppercase">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Designation / Position</label>
                    <input type="text" name="designation" value="{{ old('designation', $signatory->designation) }}" required
                           class="w-full rounded border border-gray-300 text-sm px-3 py-2">
                    @error('designation') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-1">Entity Name / LGU</label>
                    <input type="text" name="entity_name" value="{{ old('entity_name', $signatory->entity_name) }}" required
                           class="w-full rounded border border-gray-300 text-sm px-3 py-2">
                    @error('entity_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $signatory->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#1a2c5b] focus:ring-[#1a2c5b]">
                    <label class="text-sm text-gray-700">Active</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="rounded bg-[#1a2c5b] px-6 py-2 text-sm font-semibold text-white hover:bg-[#253d82] transition">Update Signatory</button>
                    <a href="{{ route('signatories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
