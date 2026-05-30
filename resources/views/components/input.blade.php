@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'leading' => null,
    'trailing' => null,
])

@php
    $classes = 'block w-full rounded-xl border bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary/50';

    if ($errors->has($attributes->get('name'))) {
        $classes .= ' border-red text-red focus:ring-red/50';
    } else {
        $classes .= ' border-neutral-200 hover:border-neutral-300';
    }
@endphp

<div>
    @if($label)
        <label for="{{ $attributes->get('id') ?? $attributes->get('name') }}" class="mb-1.5 block text-sm font-medium text-neutral-700">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if($leading)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                {!! $leading !!}
            </div>
        @endif

        <input {{ $attributes->merge(['class' => $classes . ($leading ? ' pl-10' : '') . ($trailing ? ' pr-10' : '')]) }}>

        @if($trailing)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400">
                {!! $trailing !!}
            </div>
        @endif
    </div>

    @if($error)
        <p class="mt-1.5 text-xs text-red">{{ $error }}</p>
    @endif

    @if($help)
        <p class="mt-1.5 text-xs text-neutral-500">{{ $help }}</p>
    @endif
</div>
