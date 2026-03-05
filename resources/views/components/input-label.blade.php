@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm tracking-wide text-slate-700']) }}>
    {{ $value ?? $slot }}
</label>
