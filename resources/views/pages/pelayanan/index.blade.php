<?php

use App\Models\Pelayanan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    #[Title('Layanan - KUA Connect')]
    #[Layout('layouts.app')]

    #[Computed(persist: true, seconds: 60)]
    public function daftarLayanan(): array
    {
        return Pelayanan::where('aktif', true)
            ->withCount('pendaftaran')
            ->orderBy('nama_pelayanan')
            ->get()
            ->toArray();
    }

    public function mount(): void
    {
        //
    }
};
?>

<div>
    <x-navbar />

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16">
            <div class="mb-12">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Layanan</span>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-neutral-900 sm:text-4xl">Daftar Layanan</h1>
                <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                <p class="mt-4 text-sm text-neutral-500">Pilih layanan publik yang tersedia</p>
            </div>

            @if(count($this->daftarLayanan) > 0)
                <div class=" grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->daftarLayanan as $layanan)
                        <a href="{{ route('pelayanan.show', $layanan['slug']) }}" wire:navigate
                           class=" group flex items-center gap-4 rounded-2xl border border-neutral-200/60 bg-white p-5 transition-all hover:border-brand-300 hover:shadow-[0_8px_30px_-8px_rgba(217,119,6,0.12)] hover:-translate-y-0.5">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 ring-1 ring-brand-200/50 group-hover:ring-brand-300 group-hover:shadow-md group-hover:scale-105 transition-all">
                                @php
                                    $mediaUrl = null;
                                    try { $mediaUrl = Pelayanan::find($layanan['id'])?->getFirstMediaUrl('icon', 'thumb'); } catch(\Throwable $e) {}
                                @endphp
                                @if($mediaUrl)
                                    <img src="{{ $mediaUrl }}" alt="{{ $layanan['nama_pelayanan'] }}" class="h-7 w-7 rounded object-cover" loading="lazy">
                                @else
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-neutral-900 group-hover:text-brand-700 transition-colors truncate">{{ $layanan['nama_pelayanan'] }}</p>
                                <p class="mt-0.5 text-xs text-neutral-400">{{ number_format($layanan['pendaftaran_count']) }} pendaftaran</p>
                            </div>
                            <svg class="h-4 w-4 shrink-0 text-neutral-300 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-neutral-200 bg-white p-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-medium text-neutral-900">Belum ada layanan tersedia</p>
                    <p class="mt-1 text-xs text-neutral-500">Silakan periksa kembali nanti</p>
                </div>
            @endif
        </div>
    </section>

    <x-footer />
</div>
