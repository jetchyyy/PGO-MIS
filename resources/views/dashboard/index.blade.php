@extends('layouts.app')

@section('content')
<section class="mb-6 gov-card p-6">
    <p class="gov-section-title">Property Governance Portal</p>
    <h1 class="mt-2 text-2xl font-bold text-slate-900">Provincial General Services Office</h1>
    <p class="mt-2 text-sm text-slate-600">Digital workflows for issuance, transfers, disposal, approvals, and compliance reports.</p>
</section>

<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <a href="{{ route('issuance.index') }}" class="gov-card group p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
        <div class="mb-3 inline-flex rounded-lg bg-blue-100 p-2 text-blue-800">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="3" width="16" height="18" rx="2" stroke-width="1.8"/><path stroke-width="1.8" d="M8 8h8M8 12h8M8 16h5"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-800">Issuance</h2>
        <p class="mt-1 text-sm text-slate-600">PAR/ICS issuance and property accountability.</p>
    </a>

    <a href="{{ route('transfer.index') }}" class="gov-card group p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
        <div class="mb-3 inline-flex rounded-lg bg-blue-100 p-2 text-blue-800">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M4 7h13m0 0-3-3m3 3-3 3M20 17H7m0 0 3-3m-3 3 3 3"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-800">Transfer</h2>
        <p class="mt-1 text-sm text-slate-600">PTR/ITR transfer processing and ledger updates.</p>
    </a>

    <a href="{{ route('disposal.index') }}" class="gov-card group p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
        <div class="mb-3 inline-flex rounded-lg bg-blue-100 p-2 text-blue-800">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 7h14M9 7V5h6v2m-8 0 1 12h8l1-12"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-800">Disposal</h2>
        <p class="mt-1 text-sm text-slate-600">IIRUP/RRSEP disposal and return workflows.</p>
    </a>

    <a href="{{ route('reports.index') }}" class="gov-card group p-5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
        <div class="mb-3 inline-flex rounded-lg bg-blue-100 p-2 text-blue-800">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="1.8" d="M5 20V10m7 10V4m7 16v-7"/></svg>
        </div>
        <h2 class="text-lg font-semibold text-slate-800">Reports</h2>
        <p class="mt-1 text-sm text-slate-600">Physical counts, RegSPI, print logs, and audits.</p>
    </a>
</div>
@endsection
