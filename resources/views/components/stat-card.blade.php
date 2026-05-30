@props([
    'icon' => null,
    'value' => 0,
    'label' => '',
    'color' => 'primary',
    'format' => true,
])

@php
    $iconColors = [
        'primary' => 'bg-primary-light/20 text-primary',
        'green' => 'bg-emerald-50 text-emerald-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'orange' => 'bg-brand-50 text-brand-600',
        'red' => 'bg-rose-50 text-rose-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-neutral-100 bg-white p-6 shadow-lg shadow-neutral-200/30']) }}>
    <div class="flex items-center gap-3">
        @if($icon)
            <div class="{{ $iconColors[$color] ?? $iconColors['primary'] }} flex h-12 w-12 items-center justify-center rounded-xl">
                {!! $icon !!}
            </div>
        @endif
        <div>
            <p class="text-2xl font-bold text-neutral-900 tabular-nums">{{ $format ? number_format($value) : $value }}</p>
            <p class="text-xs text-neutral-500">{{ $label }}</p>
        </div>
    </div>
</div>
