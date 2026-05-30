@php
try { $portalName = \App\Models\AppSetting::get('nama_portal', 'Tangkab Melayani'); } catch(\Throwable $e) { $portalName = 'Tangkab Melayani'; }
try { $deskripsi = \App\Models\AppSetting::get('deskripsi_portal', 'Portal Pelayanan Publik Kabupaten Tangerang'); } catch(\Throwable $e) { $deskripsi = 'Portal Pelayanan Publik Kabupaten Tangerang'; }
@endphp

<section class="relative overflow-hidden bg-white">
    <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-32">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 bg-neutral-50/50 px-3 py-1 text-xs font-medium text-neutral-500 tracking-wide">
                Portal Resmi Kabupaten Tangerang
            </span>
            <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-neutral-900 sm:text-5xl lg:text-6xl leading-[1.1]">
                Selamat Datang di
                <span class="relative whitespace-nowrap">
                    <span class="relative z-10 text-amber-600">{{ $portalName }}</span>
                    <span class="absolute -bottom-1 left-0 right-0 h-3 bg-amber-200/40 -z-0 rounded-full" aria-hidden="true"></span>
                </span>
            </h1>
            <p class="mt-4 text-base leading-relaxed text-neutral-500 sm:text-lg max-w-xl mx-auto">
                {{ $deskripsi }}
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition-all hover:bg-neutral-800 hover:shadow-xl active:scale-[0.98]">
                    Lacak Antrean
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="/layanan" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-7 py-3.5 text-sm font-medium text-neutral-700 transition-all hover:border-neutral-400 hover:bg-neutral-50 active:scale-[0.98]">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
    <div class="absolute right-0 top-0 -z-10 h-[600px] w-[600px] translate-x-1/3 -translate-y-1/4 rounded-full bg-amber-50/80 blur-3xl" aria-hidden="true"></div>
    <div class="absolute left-0 bottom-0 -z-10 h-[400px] w-[400px] -translate-x-1/4 translate-y-1/4 rounded-full bg-neutral-50 blur-3xl" aria-hidden="true"></div>
</section>
