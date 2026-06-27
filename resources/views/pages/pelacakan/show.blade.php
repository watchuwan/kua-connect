<?php

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    public Pendaftaran $pendaftaran;

    #[Title('Detail Antrean - KUA Connect')]
    #[Layout('layouts.app')]

    public function mount(Pendaftaran $pendaftaran): void
    {
        $this->pendaftaran = $pendaftaran->load('pelayanan');
    }

    public function refreshPendaftaran(): void
    {
        if ($this->pendaftaran->status !== StatusPendaftaran::Selesai && $this->pendaftaran->status !== StatusPendaftaran::Dibatalkan) {
            $this->pendaftaran->refresh();
            $this->pendaftaran->load('pelayanan');
        }
    }
};
?>

<div class="flex min-h-screen flex-col">
    <x-navbar />

    <section class="flex-1 bg-gradient-to-b from-brand-50/20 to-white">
        <div wire:poll.10s="refreshPendaftaran" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16">

            <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-neutral-500 hover:text-brand-600 transition-colors mb-8">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>

            {{-- Header --}}
            <div class="mb-8">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Detail Antrean</span>
                <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl font-mono">{{ $pendaftaran->nomor_antrean }}</h1>
                <div class="mt-3 h-0.5 w-12 bg-brand-300 rounded-full"></div>
            </div>

            {{-- Status Timeline --}}
            <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm mb-6">
                @php
                    $allStatuses = StatusPendaftaran::cases();
                    $currentIdx = $pendaftaran->status ? array_search($pendaftaran->status, $allStatuses) : false;
                    $isTerminal = $pendaftaran->status === StatusPendaftaran::Selesai || $pendaftaran->status === StatusPendaftaran::Dibatalkan;
                @endphp

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-neutral-100">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $isTerminal ? 'bg-emerald-50 text-emerald-600' : 'bg-brand-50 text-brand-600' }}">
                        @if($pendaftaran->status === StatusPendaftaran::Selesai)
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($pendaftaran->status === StatusPendaftaran::Dibatalkan)
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @else
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-neutral-900">Status Antrean</h2>
                        <p class="text-xs text-neutral-400">Terakhir diperbarui: {{ $pendaftaran->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-sm font-semibold ring-1 ring-inset" style="background-color: {{ $pendaftaran->status->getColor() === 'danger' ? '#fef2f2' : ($pendaftaran->status->getColor() === 'success' ? '#f0fdf4' : ($pendaftaran->status->getColor() === 'warning' ? '#fffbeb' : '#eef2ff')) }}; color: {{ $pendaftaran->status->getColor() === 'danger' ? '#dc2626' : ($pendaftaran->status->getColor() === 'success' ? '#16a34a' : ($pendaftaran->status->getColor() === 'warning' ? '#d97706' : '#4f46e5')) }}; --ring-color: {{ $pendaftaran->status->getColor() === 'danger' ? '#fecaca' : ($pendaftaran->status->getColor() === 'success' ? '#bbf7d0' : ($pendaftaran->status->getColor() === 'warning' ? '#fde68a' : '#c7d2fe')) }}40">
                            @if($pendaftaran->status->getIcon())
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $pendaftaran->status->getIcon() }}"/></svg>
                            @endif
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $pendaftaran->status->getColor() === 'danger' ? '#dc2626' : ($pendaftaran->status->getColor() === 'success' ? '#16a34a' : ($pendaftaran->status->getColor() === 'warning' ? '#d97706' : '#4f46e5')) }}"></span>
                            {{ $pendaftaran->status->getLabel() }}
                        </span>
                    </div>
                </div>

                {{-- Progress steps --}}
                <div class="relative">
                    <div class="absolute left-[1.125rem] top-2 bottom-2 w-px bg-neutral-200 -z-0"></div>
                    <ol class="space-y-0 relative z-10">
                        @foreach($allStatuses as $i => $s)
                            @php
                                $isPast = $currentIdx !== false && $i <= $currentIdx;
                                $isCurrent = $currentIdx !== false && $i === $currentIdx;
                                $isTerminalStatus = $s === StatusPendaftaran::Selesai || $s === StatusPendaftaran::Dibatalkan;
                                $isSkipped = $pendaftaran->status === StatusPendaftaran::Dibatalkan && !$isPast;
                            @endphp
                            <li class="flex items-start gap-4 py-3 {{ ($isCurrent || $isSkipped) ? '' : 'opacity-60' }}">
                                <div class="shrink-0 flex flex-col items-center">
                                    @if($isCurrent)
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-white text-sm font-bold shadow-sm" style="background-color: {{ $s->getColor() === 'danger' ? '#dc2626' : ($s->getColor() === 'success' ? '#16a34a' : ($s->getColor() === 'warning' ? '#d97706' : '#4f46e5')) }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s->getIcon() }}"/></svg>
                                        </span>
                                    @elseif($isPast)
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    @elseif($isSkipped)
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </span>
                                    @else
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0 pt-1.5">
                                    <p class="text-sm font-medium {{ $isCurrent ? 'text-neutral-900' : 'text-neutral-600' }}">{{ $s->getLabel() }}</p>
                                    <p class="text-xs text-neutral-400 mt-0.5">
                                        @if($s === $pendaftaran->status)
                                            Status saat ini
                                        @elseif($isTerminalStatus && $s !== $pendaftaran->status && $isTerminalStatus)
                                            @if($pendaftaran->status === StatusPendaftaran::Dibatalkan)
                                                Dibatalkan
                                            @elseif($isPast)
                                                Selesai
                                            @endif
                                        @elseif($isPast)
                                            Terlewati
                                        @else
                                            Menunggu
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            {{-- Detail Card --}}
            <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm mb-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-neutral-100">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-neutral-900">Informasi Pendaftaran</h2>
                        <p class="text-xs text-neutral-400">Detail data yang telah Anda daftarkan</p>
                    </div>
                </div>

                <div class="space-y-0">
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Layanan</span>
                        <span class="text-sm font-medium text-neutral-900 text-right max-w-[60%]">{{ $pendaftaran->pelayanan?->nama_pelayanan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Waktu Daftar</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->created_at->setTimezone('Asia/Jayapura')->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @if($pendaftaran->waktu_dilayani)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Mulai Dilayani</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->waktu_dilayani->setTimezone('Asia/Jayapura')->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->waktu_selesai)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Selesai</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->waktu_selesai->setTimezone('Asia/Jayapura')->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->jadwal_kedatangan)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Jadwal Kedatangan</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->jadwal_kedatangan->setTimezone('Asia/Jayapura')->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->no_surat)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">No. Dokumen</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $pendaftaran->no_surat }}</span>
                    </div>
                    @endif
                    @if($pendaftaran->derajat_kiblat)
                    <div class="flex justify-between py-3 border-b border-neutral-100">
                        <span class="text-sm text-neutral-500">Derajat Kiblat</span>
                        <span class="text-sm font-medium text-neutral-900 font-mono">{{ $pendaftaran->derajat_kiblat }}°</span>
                    </div>
                    @endif
                    @if($pendaftaran->catatan)
                    <div class="py-3">
                        <span class="text-sm text-neutral-500 block mb-1">Catatan</span>
                        <span class="text-sm font-medium text-neutral-900 block bg-neutral-50 rounded-lg p-3 mt-1">{{ $pendaftaran->catatan }}</span>
                    </div>
                    @endif
                </div>

                {{-- Submitted Data --}}
                @if($pendaftaran->data && count($pendaftaran->data) > 0)
                    @php
                        $allMedia = $pendaftaran->getMedia('pendaftaran_files')->groupBy('name');
                    @endphp
                    <div class="border-t border-neutral-100 pt-4 mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-3">Data yang Didaftarkan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                            @foreach($pendaftaran->data as $key => $value)
                                <div class="flex justify-between py-1.5 border-b border-neutral-50">
                                    <span class="text-sm text-neutral-500 capitalize truncate mr-2">{{ str_replace('_', ' ', $key) }}</span>
                                    <span class="text-sm font-medium text-neutral-900 text-right max-w-[55%]">
                                        @if(is_array($value))
                                            @if(isset($value['filename']))
                                                @php
                                                    $media = ($allMedia[$key] ?? collect())->first();
                                                    $isImage = isset($value['mime']) && str_starts_with($value['mime'], 'image/');
                                                @endphp
                                                @if($media)
                                                    <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                        @if($isImage)
                                                            <img src="{{ $media->getUrl() }}" alt="{{ $value['filename'] }}" class="h-12 w-12 rounded-lg object-cover border border-neutral-200 shadow-sm">
                                                        @else
                                                            <x-file-icon :filename="$value['filename']" :mime="$value['mime'] ?? null" />
                                                        @endif
                                                        <span class="text-xs text-brand-600 truncate max-w-[120px]">{{ $value['filename'] }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-xs text-brand-600">{{ $value['filename'] }}</span>
                                                @endif
                                            @else
                                                <div class="flex flex-wrap gap-2 justify-end">
                                                @foreach($value as $vi => $v)
                                                    @if(is_array($v) && isset($v['filename']))
                                                        @php
                                                            $multiMedia = ($allMedia[$key] ?? collect())[$vi] ?? null;
                                                            $isImage = isset($v['mime']) && str_starts_with($v['mime'], 'image/');
                                                        @endphp
                                                        @if($multiMedia)
                                                            <a href="{{ $multiMedia->getUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                                                                @if($isImage)
                                                                    <img src="{{ $multiMedia->getUrl() }}" alt="{{ $v['filename'] }}" class="h-10 w-10 rounded-lg object-cover border border-neutral-200 shadow-sm">
                                                                @else
                                                                    <x-file-icon :filename="$v['filename']" :mime="$v['mime'] ?? null" />
                                                                @endif
                                                                <span class="text-xs text-brand-600 truncate max-w-[100px]">{{ $v['filename'] }}</span>
                                                            </a>
                                                        @else
                                                            <span class="text-xs text-brand-600">{{ $v['filename'] }}</span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-neutral-600">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                                    @endif
                                                @endforeach
                                                </div>
                                            @endif
                                        @elseif($value instanceof \Illuminate\Support\Carbon)
                                            {{ $value->setTimezone('Asia/Jayapura')->translatedFormat('d M Y') }}
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

            {{-- Back --}}
            <div class="text-center">
                <a href="{{ route('tracking.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-brand-600 hover:text-brand-700 font-medium transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Lacak antrean lain
                </a>
            </div>
        </div>
    </section>

    <x-footer />
</div>
