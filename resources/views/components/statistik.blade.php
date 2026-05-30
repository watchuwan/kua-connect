@php
    try { $jumlahInstansi = \App\Models\Instansi::count(); } catch(\Throwable $e) { $jumlahInstansi = 0; }
    try { $jumlahPelayanan = \App\Models\Pelayanan::count(); } catch(\Throwable $e) { $jumlahPelayanan = 0; }
    try { $antreanHariIni = \App\Models\Pendaftaran::whereDate('created_at', today())->count(); } catch(\Throwable $e) { $antreanHariIni = 0; }
    try { $antreanSelesai = \App\Models\Pendaftaran::whereDate('created_at', today())->where('status', 'Done')->count(); } catch(\Throwable $e) { $antreanSelesai = 0; }
@endphp

<section class="relative -mt-10 z-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-neutral-200/70 bg-neutral-200/70 sm:grid-cols-4 shadow-[0_4px_20px_-8px_rgba(0,0,0,0.08)]">
            <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($jumlahInstansi) }}</p>
                    <p class="mt-0.5 text-xs text-neutral-500">OPD Terdaftar</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($jumlahPelayanan) }}</p>
                    <p class="mt-0.5 text-xs text-neutral-500">Layanan Aktif</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($antreanHariIni) }}</p>
                    <p class="mt-0.5 text-xs text-neutral-500">Antrean Hari Ini</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($antreanSelesai) }}</p>
                    <p class="mt-0.5 text-xs text-neutral-500">Selesai Hari Ini</p>
                </div>
            </div>
        </div>
    </div>
</section>
