@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-2 py-5 border-b-2 border-amber-500 text-sm font-semibold leading-5 text-slate-900 focus:outline-none focus:border-amber-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center gap-2 px-2 py-5 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-600 hover:text-blue-900 hover:border-blue-300 focus:outline-none focus:text-blue-900 focus:border-blue-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
