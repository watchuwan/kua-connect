<?php

use App\Models\Pendaftaran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    public Pendaftaran $pendaftaran;

    #[Title('Detail Antrean - Tangkab Melayani')]
    #[Layout('layouts.app')]

    public function mount(Pendaftaran $pendaftaran): void
    {
        $this->pendaftaran = $pendaftaran->load('pelayanan');
    }

    public function refreshPendaftaran(): void
    {
        if (!in_array($this->pendaftaran->status->value, ['done', 'skipped'])) {
            $this->pendaftaran->refresh();
            $this->pendaftaran->load('pelayanan');
        }
    }
};
?>

<div>
    <x-navbar />

    <section class="bg-neutral-50/60 min-h-screen">
        <div wire:poll.10s="refreshPendaftaran" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16">

            <a href="{{ route('tracking.index') }}" wire:navigate class="fade-up inline-flex items-center gap-1.5 text-sm text-neutral-500 hover:text-brand-600 transition-colors mb-6">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>

            <div class="fade-up mb-8">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Detail Antrean</span>
                <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl font-mono">{{ $pendaftaran->nomor_antrean }}</h1>
                <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
            </div>

            <div class="fade-up-scale rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)]">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs text-neutral-500">Status</p>
                        <span class="mt-1 inline-flex items-center gap-1.5 rounded-full px-3.5 py-1 text-sm font-semibold ring-1 ring-inset {{ $badgeColors[$status] ?? 'bg-neutral-50 text-neutral-500 ring-neutral-600/20' }}">
                            <span class="h-2 w-2 rounded-full {{ $status === 'waiting' ? 'bg-brand-500' : ($status === 'serving' ? 'bg-blue-500' : ($status === 'done' ? 'bg-emerald-500' : 'bg-neutral-400')) }}"></span>
                            {{ $badgeLabels[$status] ?? $status }}
                        </span>
                    </div>
                </div>

                <div class="space-y-0">
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Layanan</span>
                        <span class="text-sm font-medium text-neutral-900 text-right max-w-[60%]">{{ $pendaftaran->pelayanan?->nama_pelayanan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Waktu Daftar</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @if($pendaftaran->waktu_dilayani)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Mulai Dilayani</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->waktu_dilayani->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->waktu_selesai)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Selesai</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->waktu_selesai->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif

                    @if($pendaftaran->data && count($pendaftaran->data) > 0)
                        <div class="border-t border-neutral-100 pt-4 mt-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-3">Data Pendaftaran</p>
                            <div class="space-y-2">
                                @foreach($pendaftaran->data as $key => $value)
                                    <div class="flex justify-between py-1.5">
                                        <span class="text-sm text-neutral-500 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                        <span class="text-sm font-medium text-neutral-900 text-right max-w-[60%]">
                                            @if(is_array($value))
                                                {{ implode(', ', $value) }}
                                            @elseif($value instanceof \Illuminate\Support\Carbon)
                                                {{ $value->translatedFormat('d M Y') }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-brand-600 hover:text-brand-700 font-medium transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Lacak antrean lain
                </a>
            </div>
            </div>
        </div>
    </section>

    <x-footer />
</div>
