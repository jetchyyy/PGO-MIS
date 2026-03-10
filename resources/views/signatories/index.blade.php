@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Government Page Banner --}}
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Settings</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">Document Signatories</p>
            <p class="text-blue-200 text-[11px]">Manage signatories that appear on printed documents &mdash; configurable per LGU</p>
        </div>
    </div>

    {{-- Breadcrumb & Actions --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="flex w-full flex-col gap-3 px-4 py-2 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-[#1a2c5b]">Home</a>
                <span>&rsaquo;</span>
                <span class="text-[#1a2c5b] font-semibold">Signatories</span>
            </div>
            <a href="{{ route('signatories.create') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-[#253d82] sm:w-auto">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Signatory
            </a>
        </div>
    </div>

    @if(session('status'))
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 font-medium">{{ session('status') }}</div>
    </div>
    @endif

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">

        <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Active Signatories</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Role</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Name</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Designation</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Entity</th>
                            <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-widest text-gray-600">Signature</th>
                            <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-600">Status</th>
                            <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-widest text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signatories as $i => $sig)
                        <tr class="border-b border-gray-100 hover:bg-blue-50/40 {{ $i % 2 ? 'bg-gray-50/50' : '' }}">
                            <td class="px-4 py-2.5">
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase border border-blue-200 bg-blue-50 text-blue-700">
                                    {{ \App\Models\Signatory::ROLES[$sig->role_key] ?? $sig->role_key }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 font-semibold text-gray-800">{{ $sig->name }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $sig->designation }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $sig->entity_name }}</td>
                            <td class="px-4 py-2.5">
                                @if($sig->signature_url)
                                <img src="{{ $sig->signature_url }}" alt="Signature" class="h-9 object-contain">
                                @else
                                <span class="text-[11px] text-gray-400">No signature</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                @if($sig->is_active)
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase border border-emerald-200 bg-emerald-50 text-emerald-700">Active</span>
                                @else
                                <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase border border-gray-200 bg-gray-50 text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('signatories.edit', $sig) }}" class="text-[#1a2c5b] hover:underline text-xs font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('signatories.destroy', $sig) }}" onsubmit="return confirm('Delete this signatory?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline text-xs font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">No signatories configured yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 bg-white border border-gray-200 rounded shadow-sm p-5">
            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-600 mb-3">How Signatories Are Used</h3>
            <div class="text-sm text-gray-600 space-y-1.5">
                <p><strong>PGSO Head / OIC</strong> &mdash; Appears as "Issued by" on PAR & ICS, "Released/Issued by" on ITR & PTR, "Received by" on RRSEP, "Certified Correct" on WMR.</p>
                <p><strong>Governor / Head of Agency</strong> &mdash; Appears as "Approved by" on ITR & PTR, "Disposal Approved" on WMR.</p>
                <p><strong>Provincial Accountant</strong> &mdash; Appears as "Received by" on PAR (if applicable).</p>
                <p><strong>Property Inspector</strong> &mdash; Appears on WMR Certificate of Inspection.</p>
                <p><strong>COA Representative</strong> &mdash; Appears as "Witness" on WMR Certificate of Inspection.</p>
            </div>
        </div>

    </div>
</div>
@endsection
