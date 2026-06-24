<?php

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    public string $nomor_antrean = '';
    public ?Pendaftaran $result = null;
    public bool $searched = false;

    #[Title('Lacak Antrean - KUA Connect')]
    #[Layout('layouts.app')]

    public function cari(): void
    {
        $this->validate(['nomor_antrean' => 'required|string|max:20']);
        $this->searched = true;
        $this->result = Pendaftaran::with('pelayanan')
            ->where('nomor_antrean', $this->nomor_antrean)
            ->first();
    }
};
?>

<div class="flex min-h-screen flex-col">
    <x-navbar />

    <section class="flex-1 bg-gradient-to-b from-brand-50/20 to-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-12 lg:py-16">

            <div class="mb-10">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Tracking</span>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-neutral-900 sm:text-4xl">Lacak Antrean</h1>
                <div class="mt-3 h-0.5 w-12 bg-brand-300 rounded-full"></div>
                <p class="mt-4 text-sm text-neutral-500">Masukkan nomor antrean untuk mengetahui status terbaru</p>
            </div>

            <div class="">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <input
                            type="text"
                            wire:model="nomor_antrean"
                            wire:keydown.enter="cari"
                            placeholder="Masukkan nomor antrean..."
                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3.5 text-sm text-neutral-900 placeholder-neutral-400 shadow-sm transition-all focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-100"
                        >
                        @error('nomor_antrean')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button wire:click="cari" class="shrink-0 rounded-xl bg-neutral-900 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-neutral-800 active:scale-[0.98]">
                        Cari
                    </button>
                </div>
            </div>

            @if($searched)
                <div class="mt-8">
                    @if($result)
                        <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm">
                            {{-- Header --}}
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <p class="text-xs text-neutral-500">Nomor Antrean</p>
                                    <p class="text-2xl font-bold text-neutral-900 font-mono tracking-wider mt-0.5">{{ $result->nomor_antrean }}</p>
                                </div>
                                @php $status = $result->status @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-sm font-semibold ring-1 ring-inset" style="background-color: {{ $status->getColor() === 'danger' ? '#fef2f2' : ($status->getColor() === 'success' ? '#f0fdf4' : ($status->getColor() === 'warning' ? '#fffbeb' : '#eef2ff')) }}; color: {{ $status->getColor() === 'danger' ? '#dc2626' : ($status->getColor() === 'success' ? '#16a34a' : ($status->getColor() === 'warning' ? '#d97706' : '#4f46e5')) }}; --ring-color: {{ $status->getColor() === 'danger' ? '#fecaca' : ($status->getColor() === 'success' ? '#bbf7d0' : ($status->getColor() === 'warning' ? '#fde68a' : '#c7d2fe')) }}40">
                                    <span class="h-2 w-2 rounded-full" style="background-color: {{ $status->getColor() === 'danger' ? '#dc2626' : ($status->getColor() === 'success' ? '#16a34a' : ($status->getColor() === 'warning' ? '#d97706' : '#4f46e5')) }}"></span>
                                    {{ $status->getLabel() }}
                                </span>
                            </div>

                            {{-- Detail --}}
                            <div class="space-y-0">
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Layanan</span>
                                    <span class="text-sm font-medium text-neutral-900 text-right max-w-[60%]">{{ $result->pelayanan?->nama_pelayanan ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Waktu Daftar</span>
                                    <span class="text-sm font-medium text-neutral-900">{{ $result->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                @if($result->waktu_dilayani)
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Dilayani</span>
                                    <span class="text-sm font-medium text-neutral-900">{{ $result->waktu_dilayani->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                @endif
                                @if($result->waktu_selesai)
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Selesai</span>
                                    <span class="text-sm font-medium text-neutral-900">{{ $result->waktu_selesai->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                @endif
                                @if($result->jadwal_kedatangan)
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Jadwal Kedatangan</span>
                                    <span class="text-sm font-medium text-neutral-900">{{ $result->jadwal_kedatangan->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                @endif
                                @if($result->no_surat)
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">No. Dokumen</span>
                                    <span class="text-sm font-medium text-neutral-900">{{ $result->no_surat }}</span>
                                </div>
                                @endif
                                @if($result->catatan)
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-sm text-neutral-500">Catatan</span>
                                    <span class="text-sm font-medium text-neutral-900 text-right max-w-[60%]">{{ $result->catatan }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-neutral-100">
                                <a href="{{ route('tracking.show', $result->nomor_antrean) }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm font-medium text-brand-600 hover:text-brand-700 transition-colors">
                                    Lihat Detail Lengkap
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-neutral-200 bg-white p-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="mt-3 text-sm font-medium text-neutral-900">Antrean Tidak Ditemukan</p>
                            <p class="mt-1 text-xs text-neutral-500">Pastikan nomor antrean yang dimasukkan sudah benar</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <x-footer />
</div>
