@props([
    'color' => 'primary',
    'size' => 'sm',
])

@php
    $colors = [
        'primary' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'red' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'purple' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
        'orange' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
        'neutral' => 'bg-neutral-50 text-neutral-700 ring-neutral-600/20',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    $class = "inline-flex items-center gap-1 rounded-full font-medium ring-1 ring-inset $colors[$color] $sizes[$size]";
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
