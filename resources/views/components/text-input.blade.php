@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 bg-white/95 text-slate-800 focus:border-blue-800 focus:ring-blue-700 rounded-md shadow-sm']) }}>
