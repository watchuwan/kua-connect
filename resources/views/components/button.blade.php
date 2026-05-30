@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all focus:outline-none focus:ring-2 focus:ring-primary/50';

    $variants = [
        'primary' => 'bg-primary text-white shadow-lg hover:bg-primary-dark hover:shadow-xl disabled:bg-neutral-300 disabled:text-neutral-500 disabled:shadow-none',
        'secondary' => 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 disabled:opacity-50',
        'outline' => 'border-2 border-primary text-primary hover:bg-primary hover:text-white disabled:opacity-50',
        'ghost' => 'text-neutral-700 hover:bg-neutral-100 disabled:opacity-50',
        'danger' => 'bg-red text-white shadow-lg hover:bg-red-dark disabled:opacity-50',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-8 py-3.5 text-sm',
    ];

    $class = trim("$base $variants[$variant] $sizes[$size]");
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        @if($loading)
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }} @if($disabled || $loading) disabled @endif>
        @if($loading)
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        @endif
        {{ $slot }}
    </button>
@endif
