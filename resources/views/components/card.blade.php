@props([
    'padding' => true,
    'hover' => false,
    'shadow' => true,
    'center' => false,
    'paddingSize' => 'md',
])

@php
    $classes = 'rounded-2xl border border-neutral-100 bg-white';

    if ($shadow) $classes .= ' shadow-lg shadow-neutral-200/30';
    if ($hover) $classes .= ' transition-all hover:shadow-xl hover:-translate-y-0.5';
    if ($center) $classes .= ' flex flex-col items-center text-center';
    if ($padding) {
        $sizes = ['sm' => 'p-4', 'md' => 'p-6', 'lg' => 'p-8'];
        $classes .= ' ' . ($sizes[$paddingSize] ?? 'p-6');
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @isset($header)
        <div {{ $header->attributes->merge(['class' => 'mb-4' . ($center ? ' w-full' : '')]) }}>
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}

    @isset($footer)
        <div {{ $footer->attributes->merge(['class' => 'mt-4 pt-4 border-t border-neutral-100' . ($center ? ' w-full' : '')]) }}>
            {{ $footer }}
        </div>
    @endisset
</div>
