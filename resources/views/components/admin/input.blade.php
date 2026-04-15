@props(['disabled' => false, 'error' => null])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full px-5 py-4 text-slate-800 bg-slate-50 border ' . ($error ? 'border-red-500' : 'border-slate-200') . ' rounded-2xl focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 focus:outline-none transition-all duration-300']) !!}>
