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
    try { $heroImage = \App\Models\AppSetting::get('hero_image', ''); } catch(\Throwable $e) { $heroImage = ''; }
@endphp

    <x-navbar />

    {{-- Hero --}}
    <section
        x-data="heroBg"
        class="relative overflow-hidden"
    >
        {{-- Background image --}}
        <div class="absolute inset-0 -z-20" aria-hidden="true">
            <img
                src="{{ $heroImage ?: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&q=80' }}"
                alt=""
                class="h-full w-full object-cover"
                fetchpriority="high"
                x-ref="bgImg"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-brand-900/75 via-brand-800/65 to-brand-900/85"></div>
        </div>

        {{-- Dot pattern overlay --}}
        <div class="absolute inset-0 -z-10 opacity-[0.06]" aria-hidden="true">
            <div class="h-full w-full" style="background-image: radial-gradient(circle at 1.5px 1.5px, white 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>

        {{-- Animated accent blobs --}}
        <div class="absolute right-0 top-0 -z-10 h-[500px] w-[500px] translate-x-1/4 -translate-y-1/4 rounded-full bg-brand-500/10 blur-3xl" aria-hidden="true" x-ref="blobTop"></div>
        <div class="absolute left-0 bottom-0 -z-10 h-[350px] w-[350px] -translate-x-1/4 translate-y-1/4 rounded-full bg-accent-500/10 blur-3xl" aria-hidden="true" x-ref="blobBottom"></div>

        {{-- Content --}}
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-medium text-white/80 tracking-wide backdrop-blur-sm">
                    Portal Resmi Kabupaten Tangerang
                </span>
                <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl leading-[1.1]">
                    Selamat Datang di
                    <span class="relative whitespace-nowrap">
                        <span class="relative z-10 text-white">{{ $portalName ?? 'Tangkab Melayani' }}</span>
                        <span class="absolute -bottom-1 left-0 right-0 h-3 bg-white/20 -z-0 rounded-full" aria-hidden="true"></span>
                    </span>
                </h1>
                <p class="mt-4 text-base leading-relaxed text-white/70 sm:text-lg max-w-xl mx-auto">
                    {{ $deskripsi ?? 'Portal Pelayanan Publik Kabupaten Tangerang' }}
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-900 shadow-lg transition-all hover:bg-brand-50 hover:shadow-xl active:scale-[0.98]">
                        Lacak Antrean
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="/layanan" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-7 py-3.5 text-sm font-medium text-white/80 transition-all hover:border-white/40 hover:bg-white/10 active:scale-[0.98]">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats inline di hero --}}
        <div class="relative z-10 mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="fade-up grid grid-cols-2 sm:grid-cols-4 divide-x divide-white/10 rounded-2xl border border-white/10 bg-brand-900/60 backdrop-blur-md">
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none">{{ number_format($this->jumlahInstansi) }}</p>
                    <p class="mt-1 text-xs text-white/60 tracking-wide">OPD Terdaftar</p>
                </div>
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none">{{ number_format($this->jumlahPelayanan) }}</p>
                    <p class="mt-1 text-xs text-white/60 tracking-wide">Layanan Aktif</p>
                </div>
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none">{{ number_format($this->antreanHariIni) }}</p>
                    <p class="mt-1 text-xs text-white/60 tracking-wide">Antrean Hari Ini</p>
                </div>
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none">{{ number_format($this->antreanSelesai) }}</p>
                    <p class="mt-1 text-xs text-white/60 tracking-wide">Selesai Hari Ini</p>
                </div>
            </div>
        </div>

        {{-- Bottom wave --}}
        <div class="absolute bottom-0 left-0 right-0 h-20 -z-10" aria-hidden="true">
            <svg class="h-full w-full text-white" viewBox="0 0 1440 80" fill="none" preserveAspectRatio="none">
                <path d="M0 80V30C240 60 480 10 720 30C960 50 1200 10 1440 20V80H0Z" fill="currentColor"/>
            </svg>
        </div>
    </section>

    {{-- Layanan Populer --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white to-brand-50/30">
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mb-12 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-accent-600">Layanan</span>
                    <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">Layanan Populer</h2>
                    <div class="mt-3 h-0.5 w-12 bg-accent-300 rounded-full"></div>
                    <p class="mt-4 text-sm text-neutral-500 max-w-md">Layanan dengan antrean terbanyak di Kabupaten Tangerang</p>
                </div>
                <a href="/layanan" wire:navigate class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-accent-600 hover:text-accent-700 transition-colors shrink-0">
                    Lihat Semua Layanan
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if(count($this->layananPopuler) > 0)
                <div class="fade-up-child grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->layananPopuler as $i => $layanan)
                        <a href="{{ route('pelayanan.show', $layanan['id']) }}" wire:navigate class="fade-up-scale group block rounded-2xl border border-neutral-200/60 bg-white p-5 transition-all hover:border-accent-300 hover:shadow-[0_8px_30px_-8px_rgba(167,37,131,0.10)] hover:-translate-y-0.5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-accent-50 text-accent-600 ring-1 ring-accent-200/50 group-hover:ring-accent-300 group-hover:shadow-md group-hover:scale-105 transition-all">
                                    @if($mediaUrl = \App\Models\Pelayanan::find($layanan['id'])?->getFirstMediaUrl('icon', 'thumb'))
                                        <img src="{{ $mediaUrl }}" alt="{{ $layanan['nama_pelayanan'] }}" class="h-7 w-7 rounded object-cover" loading="lazy">
                                    @else
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-neutral-900 truncate group-hover:text-accent-700 transition-colors">{{ $layanan['nama_pelayanan'] }}</p>
                                    <p class="mt-0.5 text-xs text-neutral-400">{{ number_format($layanan['pendaftaran_count']) }} pendaftaran</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-neutral-300 group-hover:text-accent-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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

            <div class="mt-8 text-center sm:hidden">
                <a href="/layanan" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-accent-600 hover:text-accent-700 transition-colors">
                    Lihat Semua Layanan
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        <div class="absolute left-0 top-1/4 -z-10 h-[300px] w-[300px] -translate-x-1/3 rounded-full bg-accent-50/40 blur-3xl" aria-hidden="true"></div>
    </section>

    {{-- Cara Mudah --}}
    <section class="relative overflow-hidden bg-neutral-900">
        <div class="absolute inset-0 -z-10 opacity-[0.03" aria-hidden="true">
            <div class="h-full w-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        </div>
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-300">Panduan</span>
                <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Cara Mudah Menggunakan</h2>
                <div class="mt-3 h-0.5 w-12 bg-brand-500 rounded-full mx-auto"></div>
                <p class="mt-4 text-sm text-neutral-400">Ikuti langkah berikut untuk mendapatkan pelayanan</p>
            </div>

            @php
                $steps = [
                    ['num' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun atau login untuk mulai menggunakan layanan publik.'],
                    ['num' => '02', 'title' => 'Pilih Layanan', 'desc' => 'Pilih layanan yang sesuai dengan kebutuhan Anda.'],
                    ['num' => '03', 'title' => 'Lacak Antrean', 'desc' => 'Dapatkan nomor antrean dan estimasi waktu tunggu.'],
                    ['num' => '04', 'title' => 'Selesai', 'desc' => 'Datang sesuai jadwal dan selesaikan keperluan Anda.'],
                ];
            @endphp

            <div class="fade-up-child relative grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="absolute left-[2.25rem] top-12 bottom-12 w-px bg-neutral-700 hidden lg:block" aria-hidden="true"></div>

                @foreach($steps as $i => $step)
                    <div class="fade-up-scale relative rounded-2xl border border-neutral-700/60 bg-neutral-800/50 p-6 text-center backdrop-blur-sm transition-all hover:border-brand-600/60 hover:bg-neutral-800/80">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600/30 text-sm font-bold text-brand-300 ring-1 ring-brand-600/50 backdrop-blur-sm">
                            {{ $step['num'] }}
                        </span>
                        <div class="mt-4 flex items-center justify-center gap-2">
                            <h3 class="text-sm font-semibold text-white">{{ $step['title'] }}</h3>
                            @if(!$loop->last)
                                <svg class="hidden lg:block h-4 w-4 text-neutral-600 -mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs leading-relaxed text-neutral-400">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- OPD --}}
    @if(count($this->daftarInstansi) > 0)
        @php $items = $this->daftarInstansi; @endphp
        <section class="relative overflow-hidden bg-white">
            <div class="fade-up mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
                <div class="mb-12 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">OPD</span>
                        <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">OPD Terdaftar</h2>
                        <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                        <p class="mt-4 text-sm text-neutral-500 max-w-md">{{ count($this->daftarInstansi) }} OPD aktif melayani masyarakat</p>
                    </div>
                    <a href="/opd" wire:navigate class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors shrink-0">
                        Lihat Semua OPD
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
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
                    <button
                        @click="prevSlide"
                        x-show="canPrev()"
                        class="absolute -left-3 sm:-left-4 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 bg-white shadow-md text-neutral-400 hover:text-brand-600 hover:border-brand-200 transition-all"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <button
                        @click="nextSlide"
                        x-show="canNext()"
                        class="absolute -right-3 sm:-right-4 top-1/2 -translate-y-1/2 z-10 flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 bg-white shadow-md text-neutral-400 hover:text-brand-600 hover:border-brand-200 transition-all"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

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
                                            <p class="mt-1 text-xs text-brand-600 font-medium capitalize truncate" x-text="item.tipe"></p>
                                        </x-instansi-card>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-center gap-2">
                        <template x-for="(item, i) in items" :key="i">
                            <button
                                @click="go(i)"
                                :class="i === current ? 'w-7 bg-brand-600' : 'w-2 bg-neutral-300 hover:bg-neutral-400'"
                                class="h-2 rounded-full transition-all duration-500"
                            ></button>
                        </template>
                    </div>
                </div>

                <div class="mt-8 text-center sm:hidden">
                    <a href="/opd" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                        Lihat Semua OPD
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700">
        <div class="absolute inset-0 -z-10 opacity-[0.04]" aria-hidden="true">
            <div class="h-full w-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        </div>
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-xl">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-600/40 bg-brand-800/50 px-3 py-1 text-xs font-medium text-brand-200 tracking-wide backdrop-blur-sm">
                    Siap untuk Dilayani?
                </span>
                <h2 class="mt-6 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Ambil antrean secara online dan hemat waktu Anda
                </h2>
                <p class="mt-3 text-sm text-brand-200">
                    Tidak perlu datang pagi-pagi hanya untuk mengantre. Cukup daftar dari rumah dan dapatkan estimasi waktu.
                </p>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-800 shadow-lg transition-all hover:bg-brand-50 hover:shadow-xl active:scale-[0.98]">
                        Lacak Antrean
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="/layanan" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-brand-500/50 px-7 py-3.5 text-sm font-medium text-brand-200 transition-all hover:border-brand-400 hover:bg-brand-700/50 active:scale-[0.98]">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-footer />
</div>
