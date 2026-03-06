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
            <form method="POST" action="{{ route('signatories.update', $signatory) }}" enctype="multipart/form-data" class="p-5 space-y-4">
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

                <div class="space-y-3 rounded border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-600">Digital Signature</p>

                    @if($signatory->signature_url)
                    <div class="rounded border border-gray-200 bg-white p-3">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-2">Current Signature</p>
                        <img src="{{ $signatory->signature_url }}" alt="Current Signature" class="h-16 object-contain">
                    </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Upload New Signature Image</label>
                        <input type="file" name="signature_upload" accept=".png,.jpg,.jpeg,.webp" class="w-full rounded border border-gray-300 bg-white text-sm px-3 py-2">
                        <p class="text-[11px] text-gray-500 mt-1">Upload replaces the existing signature.</p>
                        @error('signature_upload') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Or Draw New Signature</label>
                        <div class="rounded border border-gray-300 bg-white p-2">
                            <canvas id="signaturePad" width="700" height="180" class="w-full h-36 bg-white"></canvas>
                        </div>
                        <input type="hidden" name="signature_data" id="signatureData">
                        @error('signature_data') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        <div class="mt-2 flex items-center gap-2">
                            <button type="button" id="clearSignature" class="rounded border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100">Clear</button>
                            <button type="button" id="useDrawnSignature" class="rounded border border-[#1a2c5b] bg-[#1a2c5b] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#253d82]">Use Drawn Signature</button>
                            <span id="signatureState" class="text-[11px] text-gray-500">Not saved yet</span>
                        </div>
                    </div>

                    @if($signatory->signature_url)
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="remove_signature" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                        Remove current signature
                    </label>
                    @endif
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
    <script>
(() => {
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    const dataInput = document.getElementById('signatureData');
    const clearBtn = document.getElementById('clearSignature');
    const useBtn = document.getElementById('useDrawnSignature');
    const stateEl = document.getElementById('signatureState');
    const fileInput = document.querySelector('input[name="signature_upload"]');
    let drawing = false;
    let dirty = false;

    const getPoint = (event) => {
        const rect = canvas.getBoundingClientRect();
        const source = event.touches ? event.touches[0] : event;
        return {
            x: (source.clientX - rect.left) * (canvas.width / rect.width),
            y: (source.clientY - rect.top) * (canvas.height / rect.height),
        };
    };

    const start = (event) => {
        drawing = true;
        const p = getPoint(event);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        event.preventDefault();
    };

    const draw = (event) => {
        if (!drawing) return;
        const p = getPoint(event);
        ctx.lineTo(p.x, p.y);
        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 2.2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.stroke();
        dirty = true;
        event.preventDefault();
    };

    const end = () => { drawing = false; };

    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        dataInput.value = '';
        dirty = false;
        stateEl.textContent = 'Not saved yet';
    });

    useBtn.addEventListener('click', () => {
        if (!dirty) return;
        dataInput.value = canvas.toDataURL('image/png');
        if (fileInput) fileInput.value = '';
        stateEl.textContent = 'Drawn signature ready';
    });

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    window.addEventListener('touchend', end);
})();
</script>
</div>
@endsection
