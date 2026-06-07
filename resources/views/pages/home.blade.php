<?php

use App\Enums\StatusPendaftaran;
use App\Models\Pelayanan;
use App\Models\Pendaftaran;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    #[Title('Beranda - KUA Connect')]
    #[Layout('layouts.app')]

    public function mount(): void
    {
        //
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
            ->where('status', StatusPendaftaran::Selesai->value)
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
};
?>

<div>
@php
    try { $portalName = \App\Models\AppSetting::get('nama_portal', 'KUA Connect'); } catch(\Throwable $e) { $portalName = 'KUA Connect'; }
    try { $deskripsi = \App\Models\AppSetting::get('deskripsi_portal', 'Portal Pelayanan Publik KUA Kecamatan'); } catch(\Throwable $e) { $deskripsi = 'Portal Pelayanan Publik KUA Kecamatan'; }
    try { $heroImage = \App\Models\AppSetting::get('hero_image', ''); } catch(\Throwable $e) { $heroImage = ''; }
@endphp

    <x-navbar />

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-brand-900 via-brand-800 to-brand-900">
        {{-- Islamic geometric pattern overlay --}}
        <div class="absolute inset-0 -z-10 opacity-[0.04]" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="geo" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <path d="M40 0L80 40L40 80L0 40Z" fill="none" stroke="white" stroke-width="0.5"/>
                        <circle cx="40" cy="40" r="20" fill="none" stroke="white" stroke-width="0.3"/>
                        <path d="M40 10L70 40L40 70L10 40Z" fill="none" stroke="white" stroke-width="0.2"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#geo)"/>
            </svg>
        </div>

        {{-- Masjid silhouette --}}
        <div class="absolute right-0 bottom-0 -z-10 opacity-[0.03]" aria-hidden="true">
            <svg viewBox="0 0 400 300" width="600" height="450" class="translate-x-12">
                <path d="M200 30L220 60L240 30L260 60L280 30L260 80H220Z" fill="white"/>
                <rect x="180" y="80" width="40" height="60" fill="white" rx="2"/>
                <path d="M160 140L240 140L260 280L140 280Z" fill="white"/>
                <rect x="180" y="200" width="40" height="80" fill="white" opacity="0.5"/>
                <rect x="140" y="150" width="120" height="15" fill="white" rx="2"/>
            </svg>
        </div>

        {{-- Animated accent blobs --}}
        <div class="absolute left-1/4 top-0 -z-10 h-[600px] w-[600px] -translate-y-1/3 rounded-full bg-brand-400/10 blur-3xl" aria-hidden="true"></div>
        <div class="absolute right-0 bottom-0 -z-10 h-[400px] w-[400px] translate-x-1/4 translate-y-1/4 rounded-full bg-accent-500/10 blur-3xl" aria-hidden="true"></div>

        {{-- Content --}}
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-28">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: Text --}}
                <div class="fade-up text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-medium text-white/80 tracking-wide backdrop-blur-sm">
                        Portal Resmi KUA Kecamatan
                    </span>
                    <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl leading-[1.1]">
                        Selamat Datang di
                        <span class="relative whitespace-nowrap">
                            <span class="relative z-10 text-white">{{ $portalName ?? 'KUA Connect' }}</span>
                            <span class="absolute -bottom-1 left-0 right-0 h-3 bg-white/15 -z-0 rounded-full" aria-hidden="true"></span>
                        </span>
                    </h1>
                    <p class="mt-4 text-base leading-relaxed text-white/70 sm:text-lg max-w-xl mx-auto lg:mx-0">
                        {{ $deskripsi ?? 'Portal Pelayanan Publik KUA Kecamatan' }}
                    </p>
                    <p class="mt-2 text-sm text-white/50 max-w-lg mx-auto lg:mx-0">
                        Nikah, Wakaf, Rekomendasi Nikah, Surat Keterangan Mualaf, Kalibrasi Arah Kiblat — semua bisa diurus secara online.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center lg:justify-start justify-center gap-4">
                        <a href="{{ route('pelayanan.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-900 shadow-lg transition-all hover:bg-brand-50 hover:shadow-xl active:scale-[0.98]">
                            Daftar Pelayanan
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-7 py-3.5 text-sm font-medium text-white/80 transition-all hover:border-white/40 hover:bg-white/10 active:scale-[0.98]">
                            Lacak Antrean
                        </a>
                    </div>
                </div>

                {{-- Right: Feature cards --}}
                <div class="fade-up hidden lg:grid grid-cols-2 gap-4">
                    <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all hover:bg-white/10 hover:border-white/20">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-400/20 text-brand-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-white">Pendaftaran Nikah</p>
                        <p class="mt-1 text-xs text-white/50">Daftar nikah online, verifikasi dokumen, tentukan jadwal akad</p>
                    </div>
                    <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all hover:bg-white/10 hover:border-white/20 translate-y-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent-400/20 text-accent-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-white">Sertifikasi Wakaf</p>
                        <p class="mt-1 text-xs text-white/50">Ajukan legalitas aset wakaf, jadwalkan ikrar di KUA</p>
                    </div>
                    <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all hover:bg-white/10 hover:border-white/20">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-400/20 text-brand-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-white">SK Mualaf & Rekomendasi Nikah</p>
                        <p class="mt-1 text-xs text-white/50">Urus surat keterangan mualaf dan rekomendasi nikah pindah</p>
                    </div>
                    <div class="group rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur-sm transition-all hover:bg-white/10 hover:border-white/20 translate-y-6">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent-400/20 text-accent-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-white">Kalibrasi Arah Kiblat</p>
                        <p class="mt-1 text-xs text-white/50">Request pengukuran presisi arah kiblat masjid & mushala</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats inline di hero --}}
        <div class="relative mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="fade-up grid grid-cols-2 sm:grid-cols-4 divide-x divide-white/10 rounded-2xl border border-white/10 bg-brand-900/60 backdrop-blur-md">
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none" x-data x-intersect="$el.textContent = '{{ $this->jumlahPelayanan }}'">{{ number_format($this->jumlahPelayanan) }}</p>
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
                <div class="fade-up-scale p-5 sm:p-7 text-center">
                    <p class="text-2xl font-bold text-white tabular-nums leading-none">{{ number_format($this->totalPendaftaran) }}</p>
                    <p class="mt-1 text-xs text-white/60 tracking-wide">Total Terdaftar</p>
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
                    <p class="mt-4 text-sm text-neutral-500 max-w-md">Layanan keagamaan dengan antrean terbanyak</p>
                </div>
                <a href="/layanan" wire:navigate class="hidden sm:inline-flex items-center gap-1.5 text-sm font-medium text-accent-600 hover:text-accent-700 transition-colors shrink-0">
                    Lihat Semua Layanan
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if(count($this->layananPopuler) > 0)
                <div class="fade-up-child grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->layananPopuler as $i => $layanan)
                        <a href="{{ route('pelayanan.show', $layanan['slug']) }}" wire:navigate class="fade-up-scale group block rounded-2xl border border-neutral-200/60 bg-white p-5 transition-all hover:border-accent-300 hover:shadow-[0_8px_30px_-8px_rgba(167,37,131,0.10)] hover:-translate-y-0.5">
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

    {{-- Alur Layanan --}}
    <section class="relative overflow-hidden bg-neutral-900">
        <div class="absolute inset-0 -z-10 opacity-[0.03]" aria-hidden="true">
            <div class="h-full w-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        </div>
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-300">Panduan</span>
                <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">Alur Layanan KUA</h2>
                <div class="mt-3 h-0.5 w-12 bg-brand-500 rounded-full mx-auto"></div>
                <p class="mt-4 text-sm text-neutral-400">Ikuti langkah berikut untuk mendapatkan layanan KUA secara online</p>
            </div>

            @php
                $steps = [
                    ['num' => '01', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Pilih Layanan', 'desc' => 'Pilih layanan keagamaan yang Anda butuhkan — Nikah, Wakaf, Rekomendasi, Mualaf, atau Kalibrasi Kiblat.'],
                    ['num' => '02', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z', 'title' => 'Isi Data & Upload Dokumen', 'desc' => 'Lengkapi formulir digital dan unggah dokumen persyaratan. Sistem akan meminta dokumen tambahan secara otomatis.'],
                    ['num' => '03', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Verifikasi & Jadwal', 'desc' => 'Staf KUA memverifikasi berkas Anda. Jika sesuai, Anda akan mendapat jadwal kedatangan atau jadwal ikrar.'],
                    ['num' => '04', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Selesai & Terbit Dokumen', 'desc' => 'Setelah proses selesai, dokumen resmi (Buku Nikah, AIW, SK Mualaf, Sertifikat Kiblat) diterbitkan.'],
                ];
            @endphp

            <div class="fade-up-child relative grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="absolute left-[2.25rem] top-12 bottom-12 w-px bg-neutral-700 hidden lg:block" aria-hidden="true"></div>

                @foreach($steps as $i => $step)
                    <div class="fade-up-scale relative rounded-2xl border border-neutral-700/60 bg-neutral-800/50 p-6 text-center backdrop-blur-sm transition-all hover:border-brand-600/60 hover:bg-neutral-800/80">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600/30 text-sm font-bold text-brand-300 ring-1 ring-brand-600/50 backdrop-blur-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/></svg>
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

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700">
        <div class="absolute inset-0 -z-10 opacity-[0.04]" aria-hidden="true">
            <div class="h-full w-full" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        </div>
        <div class="fade-up mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-xl">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-600/40 bg-brand-800/50 px-3 py-1 text-xs font-medium text-brand-200 tracking-wide backdrop-blur-sm">
                    Mudah & Cepat
                </span>
                <h2 class="mt-6 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                    Urus layanan KUA dari rumah, tanpa antre panjang
                </h2>
                <p class="mt-3 text-sm text-brand-200">
                    Daftar secara online, upload dokumen, dapatkan jadwal, dan terbitan dokumen resmi — semua dalam satu platform.
                </p>
                <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="{{ route('pelayanan.index') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-800 shadow-lg transition-all hover:bg-brand-50 hover:shadow-xl active:scale-[0.98]">
                        Lihat Semua Layanan                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-footer />
</div>
