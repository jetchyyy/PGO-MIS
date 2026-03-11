@extends('layouts.app')

@section('content')
@php
    $returnOptions = $approvedReturns->mapWithKeys(function ($return) {
        return [
            $return->id => [
                'id' => $return->id,
                'control_no' => $return->control_no,
                'document_type' => $return->document_type,
                'employee_name' => $return->employee->name ?? '',
                'fund_cluster_code' => $return->fundCluster->code ?? '',
                'lines' => $return->lines->map(fn ($line) => [
                    'particulars' => $line->particulars,
                    'property_no' => $line->property_no,
                    'quantity' => $line->quantity,
                    'unit_cost' => (float) $line->unit_cost,
                    'total_cost' => (float) $line->total_cost,
                ])->values()->all(),
            ],
        ];
    });
@endphp
<div class="min-h-screen bg-gray-100" x-data="disposalFromReturn()">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-start">
            <div class="text-left">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Property Disposal</p>
                <p class="text-white font-bold text-lg leading-tight mt-0.5">Create Disposal From Approved Return</p>
            </div>
        </div>
    </div>

    @if(session('status'))
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 font-medium">{{ session('status') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pt-4">
        <div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="w-full px-4 py-5 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('disposal.store') }}" class="space-y-4">
            @csrf

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Approved Return Reference</h2>
                </div>
                <div class="p-5 grid gap-4 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5 lg:col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Approved Return Record</label>
                        <select name="property_return_id" x-model="selectedReturnId" @change="syncReturn()" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                            <option value="">Select approved PRS / RRSP</option>
                            @foreach($approvedReturns as $return)
                            <option value="{{ $return->id }}" {{ (string) $selectedReturnId === (string) $return->id ? 'selected' : '' }}>
                                {{ $return->control_no }} | {{ $return->document_type }} | {{ $return->employee->name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disposal Date</label>
                        <input type="date" name="disposal_date" value="{{ old('disposal_date', now()->toDateString()) }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm focus:border-[#1a2c5b] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1a2c5b]" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disposal Record Type</label>
                        <input type="hidden" name="document_type" :value="documentType">
                        <div class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-800">
                            <span class="font-semibold" x-text="documentType || 'Select return first'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Disposal Details</h2>
                </div>
                <div class="p-5 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Items for Disposal</label>
                        <select name="item_disposal_condition" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm" required>
                            <option value="unserviceable">Unserviceable</option>
                            <option value="no_longer_needed">No Longer Needed</option>
                            <option value="obsolete">Obsolete</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Specify Disposal Category</label>
                        <input name="item_disposal_condition_other" value="{{ old('item_disposal_condition_other') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disposal Method</label>
                        <select name="disposal_method" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm" required>
                            <option value="public_auction">Public Auction</option>
                            <option value="destruction">Destruction</option>
                            <option value="throwing">Throwing</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Specify Disposal Method</label>
                        <input name="disposal_method_other" value="{{ old('disposal_method_other') }}" class="rounded border border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-[#1a2c5b]">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Return Summary</h2>
                </div>
                <div class="p-5" x-show="selectedReturn">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-4">
                        <div><p class="text-xs uppercase text-gray-500">Return No.</p><p class="font-semibold text-gray-800" x-text="selectedReturn?.control_no"></p></div>
                        <div><p class="text-xs uppercase text-gray-500">Form Type</p><p class="font-semibold text-gray-800" x-text="selectedReturn?.document_type"></p></div>
                        <div><p class="text-xs uppercase text-gray-500">Officer</p><p class="font-semibold text-gray-800" x-text="selectedReturn?.employee_name"></p></div>
                        <div><p class="text-xs uppercase text-gray-500">Fund Cluster</p><p class="font-semibold text-gray-800" x-text="selectedReturn?.fund_cluster_code"></p></div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center">
                <button type="submit" :disabled="!selectedReturnId" :class="!selectedReturnId ? 'cursor-not-allowed border-gray-300 bg-gray-300 text-white hover:bg-gray-300' : 'border-[#1a2c5b] bg-[#1a2c5b] text-white hover:bg-[#253d82]'" class="inline-flex w-full items-center justify-center gap-2 rounded border px-6 py-2.5 text-sm font-semibold transition sm:w-auto">
                    Save Draft
                </button>
                <a href="{{ route('disposal.index') }}" class="rounded border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50 sm:w-auto">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function disposalFromReturn() {
    const options = @json($returnOptions);

    return {
        selectedReturnId: @json(old('property_return_id', $selectedReturnId ? (string) $selectedReturnId : '')),
        selectedReturn: null,
        documentType: '',
        init() {
            this.syncReturn();
        },
        syncReturn() {
            this.selectedReturn = this.selectedReturnId ? options[this.selectedReturnId] || null : null;
            this.documentType = this.selectedReturn?.document_type === 'PRS' ? 'IIRUP' : (this.selectedReturn ? 'RRSEP' : '');
        },
    };
}
</script>
@endsection
