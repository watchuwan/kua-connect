@props([
    'loading' => false,
])

@php
    $classes = 'inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-primary shadow-lg transition-all hover:bg-primary-dark hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary/50 disabled:bg-neutral-300 disabled:text-neutral-500 disabled:shadow-none';
@endphp

<button type="submit" {{ $attributes->merge(['class' => $classes]) }} @if($loading) disabled @endif>
    @if($loading)
        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
    @endif
    {{ $slot }}
</button>
