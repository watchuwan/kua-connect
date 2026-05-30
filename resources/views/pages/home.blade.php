<?php

use App\Models\Instansi;
use App\Models\Pelayanan;
use App\Models\Pendaftaran;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    #[Title('Beranda - Tangkab Melayani')]
    #[Layout('layouts.app')]

    public function mount(): void
    {
        //
    }

    #[Computed(persist: true, seconds: 120)]
    public function jumlahInstansi(): int
    {
        return Instansi::count();
    }

    #[Computed(persist: true, seconds: 120)]
    public function jumlahPelayanan(): int
    {
        return Pelayanan::where('aktif', true)->count();
    }

    #[Computed(persist: true, seconds: 30)]
    public function antreanHariIni(): int
    {
        return Pendaftaran::whereDate('created_at', today())->count();
    }

    #[Computed(persist: true, seconds: 30)]
    public function antreanSelesai(): int
    {
        return Pendaftaran::whereDate('created_at', today())
            ->where('status', 'Done')
            ->count();
    }

    #[Computed(persist: true, seconds: 120)]
    public function totalPendaftaran(): int
    {
        return Pendaftaran::count();
    }

    #[Computed(persist: true, seconds: 120)]
    public function jumlahFormFields(): int
    {
        return \App\Models\FormField::where('active', true)->count();
    }

    #[Computed(persist: true, seconds: 60)]
    public function layananPopuler(): array
    {
        return Pelayanan::where('aktif', true)
            ->withCount('pendaftaran')
            ->orderByDesc('pendaftaran_count')
            ->take(6)
            ->get()
            ->toArray();
    }

    #[Computed(persist: true, seconds: 120)]
    public function kategoriInstansi(): array
    {
        return Instansi::selectRaw("tipe, count(*) as total")
            ->groupBy('tipe')
            ->pluck('total', 'tipe')
            ->toArray();
    }

    #[Computed(persist: true, seconds: 120)]
    public function daftarInstansi(): array
    {
        return Instansi::where('aktif', true)
            ->orderBy('nama_instansi')
            ->take(8)
            ->get()
            ->toArray();
    }
};
?>

<div>
@php
    try { $portalName = \App\Models\AppSetting::get('nama_portal', 'Tangkab Melayani'); } catch(\Throwable $e) { $portalName = 'Tangkab Melayani'; }
    try { $deskripsi = \App\Models\AppSetting::get('deskripsi_portal', 'Portal Pelayanan Publik Kabupaten Tangerang'); } catch(\Throwable $e) { $deskripsi = 'Portal Pelayanan Publik Kabupaten Tangerang'; }
@endphp

    <x-navbar />

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-32">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 bg-neutral-50/50 px-3 py-1 text-xs font-medium text-neutral-500 tracking-wide">
                    Portal Resmi Kabupaten Tangerang
                </span>
                <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-neutral-900 sm:text-5xl lg:text-6xl leading-[1.1]">
                    Selamat Datang di
                    <span class="relative whitespace-nowrap">
                        <span class="relative z-10 text-amber-600">{{ $portalName ?? 'Tangkab Melayani' }}</span>
                        <span class="absolute -bottom-1 left-0 right-0 h-3 bg-amber-200/40 -z-0 rounded-full" aria-hidden="true"></span>
                    </span>
                </h1>
                <p class="mt-4 text-base leading-relaxed text-neutral-500 sm:text-lg max-w-xl mx-auto">
                    {{ $deskripsi ?? 'Portal Pelayanan Publik Kabupaten Tangerang' }}
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

    {{-- Statistik --}}
    <section class="relative -mt-10 z-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-neutral-200/70 bg-neutral-200/70 sm:grid-cols-4 shadow-[0_4px_20px_-8px_rgba(0,0,0,0.08)]">
                <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($this->jumlahInstansi) }}</p>
                        <p class="mt-0.5 text-xs text-neutral-500">OPD Terdaftar</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($this->jumlahPelayanan) }}</p>
                        <p class="mt-0.5 text-xs text-neutral-500">Layanan Aktif</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($this->antreanHariIni) }}</p>
                        <p class="mt-0.5 text-xs text-neutral-500">Antrean Hari Ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 bg-white p-5 sm:p-7">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-neutral-100 text-neutral-600 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900 tabular-nums leading-none">{{ number_format($this->antreanSelesai) }}</p>
                        <p class="mt-0.5 text-xs text-neutral-500">Selesai Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan Populer --}}
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mb-12">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-amber-600">Layanan</span>
                <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">Layanan Populer</h2>
                <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                <p class="mt-4 text-sm text-neutral-500 max-w-md">Layanan dengan antrean terbanyak di Kabupaten Tangerang</p>
            </div>

            @if(count($this->layananPopuler) > 0)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->layananPopuler as $i => $layanan)
                        <a href="{{ route('pelayanan.show', $layanan['id']) }}" wire:navigate class="group block rounded-2xl border border-neutral-200/60 bg-white p-5 transition-all hover:border-amber-300 hover:shadow-[0_8px_30px_-8px_rgba(217,119,6,0.12)] hover:-translate-y-0.5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-200/50 group-hover:ring-amber-300 group-hover:shadow-md group-hover:scale-105 transition-all">
                                    @if($mediaUrl = \App\Models\Pelayanan::find($layanan['id'])?->getFirstMediaUrl('icon', 'thumb'))
                                        <img src="{{ $mediaUrl }}" alt="{{ $layanan['nama_pelayanan'] }}" class="h-7 w-7 rounded object-cover">
                                    @else
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-neutral-900 truncate group-hover:text-amber-700 transition-colors">{{ $layanan['nama_pelayanan'] }}</p>
                                    <p class="mt-0.5 text-xs text-neutral-400">{{ number_format($layanan['pendaftaran_count']) }} pendaftaran</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-neutral-300 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-card class="text-center py-12 border-dashed border-neutral-200" :shadow="false" :padding="false">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <p class="mt-3 text-sm text-neutral-500">Belum ada layanan tersedia.</p>
                </x-card>
            @endif
        </div>
    </section>

    {{-- Cara Mudah --}}
    <section class="bg-neutral-50/60">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-amber-600">Panduan</span>
                <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">Cara Mudah Menggunakan</h2>
                <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full mx-auto"></div>
                <p class="mt-4 text-sm text-neutral-500">Ikuti langkah berikut untuk mendapatkan pelayanan</p>
            </div>

            @php
                $steps = [
                    ['num' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun atau login untuk mulai menggunakan layanan publik.'],
                    ['num' => '02', 'title' => 'Pilih Layanan', 'desc' => 'Pilih layanan yang sesuai dengan kebutuhan Anda.'],
                    ['num' => '03', 'title' => 'Ambil Antrean', 'desc' => 'Dapatkan nomor antrean dan estimasi waktu tunggu.'],
                    ['num' => '04', 'title' => 'Selesai', 'desc' => 'Datang sesuai jadwal dan selesaikan keperluan Anda.'],
                ];
            @endphp

            <div class="relative grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="absolute left-[2.25rem] top-12 bottom-12 w-px bg-neutral-200 hidden lg:block" aria-hidden="true"></div>

                @foreach($steps as $i => $step)
                    <div class="relative rounded-2xl border border-neutral-200/60 bg-white p-6 text-center transition-all hover:border-amber-200/60 hover:shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)]">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-sm font-bold text-amber-600 ring-1 ring-amber-200/50">
                            {{ $step['num'] }}
                        </span>
                        <h3 class="mt-4 text-sm font-semibold text-neutral-900">{{ $step['title'] }}</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-neutral-500">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- OPD --}}
    @if(count($this->daftarInstansi) > 0)
        @php $items = $this->daftarInstansi; @endphp
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
                <div class="mb-12">
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-amber-600">OPD</span>
                    <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">OPD Terdaftar</h2>
                    <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                    <p class="mt-4 text-sm text-neutral-500 max-w-md">{{ count($this->daftarInstansi) }} OPD aktif melayani masyarakat</p>
                </div>

                <div
                    x-data="{
                        items: {{ json_encode($items) }},
                        current: 0,
                        interval: null,
                        get perView() {
                            if (window.innerWidth < 640) return 1;
                            if (window.innerWidth < 1024) return 2;
                            return 3;
                        },
                        get max() { return Math.max(0, this.items.length - this.perView); },
                        get prev() { return Math.max(0, this.current - 1); },
                        get next() { return Math.min(this.max, this.current + 1); },
                        canPrev() { return this.current > 0; },
                        canNext() { return this.current < this.max; },
                        go(i) { this.current = Math.min(this.max, Math.max(0, i)); this.resetTimer(); },
                        prevSlide() { if (this.canPrev()) { this.current = this.prev; } this.resetTimer(); },
                        nextSlide() { if (this.canNext()) { this.current = this.next; } this.resetTimer(); },
                        resetTimer() {
                            clearInterval(this.interval);
                            this.interval = setInterval(() => { if (this.canNext()) this.current = this.next; else this.current = 0; }, 4000);
                        },
                        init() {
                            this.interval = setInterval(() => { if (this.canNext()) this.current = this.next; else this.current = 0; }, 4000);
                        }
                    }"
                    class="relative"
                >
                    {{-- Prev --}}
                    <button
                        @click="prevSlide"
                        x-show="canPrev()"
                        class="absolute -left-3 sm:-left-4 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 bg-white shadow-md text-neutral-400 hover:text-amber-600 hover:border-amber-200 transition-all"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    {{-- Next --}}
                    <button
                        @click="nextSlide"
                        x-show="canNext()"
                        class="absolute -right-3 sm:-right-4 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 bg-white shadow-md text-neutral-400 hover:text-amber-600 hover:border-amber-200 transition-all"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    {{-- Track --}}
                    <div class="overflow-hidden rounded-xl">
                        <div
                            class="flex transition-transform duration-500 ease-out"
                            :style="'transform: translateX(-' + (current * (100 / perView)) + '%)'"
                        >
                            <template x-for="item in items" :key="item.id">
                                <div
                                    class="shrink-0 px-3"
                                    :style="'width: ' + (100 / perView) + '%'"
                                >
                                    <a :href="'/opd/' + item.id" wire:navigate class="block cursor-pointer">
                                        <x-instansi-card>
                                            <x-slot:badge>
                                                <span x-text="item.nama_instansi.substring(0, 2).toUpperCase()"></span>
                                            </x-slot:badge>
                                            <p class="text-sm sm:text-[15px] font-semibold text-neutral-900 leading-snug line-clamp-2" x-text="item.nama_instansi"></p>
                                            <p class="mt-1 text-xs text-amber-600 font-medium capitalize truncate" x-text="item.tipe"></p>
                                        </x-instansi-card>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Dots --}}
                    <div class="mt-6 flex items-center justify-center gap-2">
                        <template x-for="(item, i) in items" :key="i">
                            <button
                                @click="go(i)"
                                :class="i === current ? 'w-7 bg-amber-600' : 'w-2 bg-neutral-300 hover:bg-neutral-400'"
                                class="h-2 rounded-full transition-all duration-500"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="border-t border-neutral-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-xl">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 bg-neutral-50/50 px-3 py-1 text-xs font-medium text-neutral-500 tracking-wide">
                    Siap untuk Dilayani?
                </span>
                <h2 class="mt-6 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">
                    Ambil antrean secara online dan hemat waktu Anda
                </h2>
                <p class="mt-3 text-sm text-neutral-500">
                    Tidak perlu datang pagi-pagi hanya untuk mengantre. Cukup daftar dari rumah dan dapatkan estimasi waktu.
                </p>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition-all hover:bg-neutral-800 hover:shadow-xl active:scale-[0.98]">
                        Ambil Antrean
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="/layanan" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 px-7 py-3.5 text-sm font-medium text-neutral-700 transition-all hover:border-neutral-400 hover:bg-neutral-50 active:scale-[0.98]">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-footer />
</div>
