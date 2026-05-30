@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'py-16 sm:py-24',
    'background' => null,
    'maxWidth' => 'max-w-7xl',
])

@php
    $bgClasses = [
        'white' => 'bg-white',
        'warm' => 'section-warm',
        'cool' => 'section-cool',
        'primary' => 'bg-gradient-to-br from-primary via-primary-dark to-emerald-900',
        'dark' => 'bg-neutral-900',
    ][$background] ?? '';
@endphp

<section {{ $attributes->merge(['class' => trim("relative $bgClasses")]) }}>
    <div class="mx-auto px-4 sm:px-6 lg:px-8 {{ $padding }} {{ $maxWidth }}">
        @if($title || $subtitle)
            <div class="mx-auto max-w-3xl text-center mb-12">
                @if($subtitle)
                    <p class="text-sm font-semibold uppercase tracking-widest text-primary mb-2">{{ $subtitle }}</p>
                @endif
                @if($title)
                    <h2 class="font-display text-3xl font-bold tracking-tight text-neutral-900 sm:text-4xl">{{ $title }}</h2>
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</section>
