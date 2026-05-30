<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center rounded-2xl border border-neutral-200/70 bg-white p-5 sm:p-7 transition-all hover:border-brand-300 hover:shadow-[0_8px_32px_-8px_rgba(12,34,111,0.12)] group h-[220px] sm:h-[250px] justify-center overflow-hidden']) }}>
    @isset($badge)
        <div class="flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-brand-50 text-lg sm:text-xl font-bold text-brand-600 ring-1 ring-brand-200/50 group-hover:ring-brand-300 group-hover:shadow-md group-hover:scale-110 transition-all">
            {{ $badge }}
        </div>
    @endisset

    <div class="mt-3 sm:mt-4">
        {{ $slot }}
    </div>
</div>
