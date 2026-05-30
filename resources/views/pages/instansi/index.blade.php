<?php

use App\Models\Instansi;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new class extends Component
{
    #[Title('OPD - Tangkab Melayani')]
    #[Layout('layouts.app')]

    #[Computed(persist: true, seconds: 120)]
    public function daftarInstansi(): array
    {
        return Instansi::where('aktif', true)
            ->orderBy('nama_instansi')
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
            <div class="fade-up mb-12">
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">OPD</span>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-neutral-900 sm:text-4xl">OPD</h1>
                <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                <p class="mt-4 text-sm text-neutral-500">{{ count($this->daftarInstansi) }} OPD aktif melayani masyarakat</p>
            </div>

            @if(count($this->daftarInstansi) > 0)
                <div class="fade-up-child grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($this->daftarInstansi as $instansi)
                        <a href="{{ route('instansi.show', $instansi['id']) }}" wire:navigate
                           class="fade-up-scale flex flex-col items-center text-center rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-7 transition-all hover:border-brand-300 hover:shadow-[0_8px_30px_-8px_rgba(217,119,6,0.12)] group h-[220px] sm:h-[250px] justify-center overflow-hidden">
                            <div class="flex h-14 w-14 sm:h-16 sm:w-16 items-center justify-center rounded-2xl bg-brand-50 text-lg sm:text-xl font-bold text-brand-600 ring-1 ring-brand-200/50 group-hover:ring-brand-300 group-hover:shadow-md group-hover:scale-110 transition-all">
                                {{ strtoupper(substr($instansi['nama_instansi'], 0, 2)) }}
                            </div>
                            <div class="mt-3 sm:mt-4">
                                <p class="text-sm sm:text-[15px] font-semibold text-neutral-900 leading-snug line-clamp-2">{{ $instansi['nama_instansi'] }}</p>
                                <p class="mt-1 text-xs text-brand-600 font-medium capitalize truncate">{{ $instansi['tipe'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="fade-up rounded-2xl border border-dashed border-neutral-200 bg-white p-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-medium text-neutral-900">Belum ada OPD tersedia</p>
                    <p class="mt-1 text-xs text-neutral-500">Silakan periksa kembali nanti</p>
                </div>
            @endif
        </div>
    </section>

    <x-footer />
</div>
