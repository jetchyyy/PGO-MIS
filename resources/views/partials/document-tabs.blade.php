@if(! empty($documents))
<div class="bg-white border border-gray-200 rounded shadow-sm overflow-hidden">
    <div class="px-4 py-2.5 bg-[#1a2c5b] border-b border-blue-900">
        <h2 class="text-xs font-bold uppercase tracking-widest text-[#c8a84b]">Generated Documents</h2>
    </div>
    <div class="border-b border-gray-200 bg-gray-50 px-3 py-2">
        <div class="flex flex-wrap gap-2">
            @foreach($documents as $document)
            <button type="button"
                @click="activeDocument = '{{ $document['key'] }}'"
                :class="activeDocument === '{{ $document['key'] }}' ? 'border-[#1a2c5b] bg-[#1a2c5b] text-white' : 'border-gray-300 bg-white text-gray-600 hover:bg-gray-100'"
                class="rounded border px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider transition">
                {{ $document['code'] }}
            </button>
            @endforeach
        </div>
    </div>
    <div class="border-b border-gray-200 bg-white p-4">
        @foreach($documents as $document)
        <div x-show="activeDocument === '{{ $document['key'] }}'" x-cloak>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-gray-500">{{ $document['code'] }}</p>
            <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-lg font-bold text-[#1a2c5b]">{{ $document['title'] }}</p>
                    <p class="mt-1 font-mono text-sm font-bold text-gray-800">{{ $document['control_no'] }}</p>
                </div>
                @if($document['printable'] ?? true)
                <a href="{{ route($routeName, [$record, $document['template']]) }}" target="_blank"
                    class="inline-flex items-center justify-center gap-2 rounded border border-[#1a2c5b] bg-[#1a2c5b] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#253d82]">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Open {{ $document['code'] }}
                </a>
                @else
                <span class="inline-flex items-center justify-center rounded border border-gray-300 bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-600">
                    Control Number Only
                </span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="p-3">
        @foreach($documents as $document)
        <div x-show="activeDocument === '{{ $document['key'] }}'" x-cloak class="rounded border border-gray-200 bg-gray-50 p-3">
            <p class="text-[11px] font-medium uppercase tracking-widest text-gray-500">Separate Control Number</p>
            <p class="mt-1 font-mono text-sm font-bold text-gray-800">{{ $document['control_no'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
