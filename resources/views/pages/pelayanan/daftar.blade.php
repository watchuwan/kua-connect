<?php

use App\Models\FormField;
use App\Models\Pelayanan;
use App\Models\Pendaftaran;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public Pelayanan $pelayanan;
    public array $data = [];
    public array $uploads = [];
    public ?string $nomor_antrean = null;
    public bool $success = false;
    public ?int $pendaftaran_id = null;

    #[Layout('layouts.app')]

    public function mount(Pelayanan $pelayanan): void
    {
        $this->pelayanan = $pelayanan;
        foreach ($this->formFields as $field) {
            $this->data[$field->name] = '';
        }
    }

    #[Computed]
    public function formFields(): \Illuminate\Support\Collection
    {
        return $this->pelayanan->formFields()
            ->where('active', true)
            ->orderBy('order')
            ->get();
    }

    #[Computed]
    public function hasFileFields(): bool
    {
        return $this->formFields->contains(fn ($f) => $f->isFileUpload());
    }

    public function getTitle(): string
    {
        return 'Daftar ' . $this->pelayanan->nama_pelayanan . ' - Tangkab Melayani';
    }

    public function rules(): array
    {
        $rules = [];
        foreach ($this->formFields as $field) {
            $fieldRules = $field->getValidationRule();
            $name = $field->isFileUpload() ? "uploads.{$field->name}" : "data.{$field->name}";
            $rules[$name] = $fieldRules;
        }
        return $rules;
    }

    public function validationAttributes(): array
    {
        $attrs = [];
        foreach ($this->formFields as $field) {
            $name = $field->isFileUpload() ? "uploads.{$field->name}" : "data.{$field->name}";
            $attrs[$name] = $field->label;
        }
        return $attrs;
    }

    public function daftar(): void
    {
        $this->validate();

        $today = now()->format('Ymd');
        $last = Pendaftaran::where('nomor_antrean', 'like', "A-{$today}%")
            ->orderByDesc('nomor_antrean')
            ->value('nomor_antrean');
        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        $nomor = sprintf('A-%s%04d', $today, $sequence);

        $submittedData = [];
        foreach ($this->formFields as $field) {
            if ($field->isFileUpload()) {
                if (!empty($this->uploads[$field->name])) {
                    $submittedData[$field->name] = [
                        'filename' => $this->uploads[$field->name]->getClientOriginalName(),
                        'size' => $this->uploads[$field->name]->getSize(),
                        'mime' => $this->uploads[$field->name]->getMimeType(),
                    ];
                }
            } else {
                $submittedData[$field->name] = $this->data[$field->name] ?? '';
            }
        }

        $pendaftaran = Pendaftaran::create([
            'nomor_antrean' => $nomor,
            'pelayanan_id' => $this->pelayanan->id,
            'data' => $submittedData,
            'status' => 'waiting',
        ]);

        foreach ($this->formFields as $field) {
            if ($field->isFileUpload() && !empty($this->uploads[$field->name])) {
                $pendaftaran->addMedia($this->uploads[$field->name]->getRealPath())
                    ->usingName($field->name)
                    ->usingFileName($this->uploads[$field->name]->getClientOriginalName())
                    ->toMediaCollection('pendaftaran_files');
            }
        }

        $this->nomor_antrean = $pendaftaran->nomor_antrean;
        $this->success = true;
    }

    public function resetForm(): void
    {
        $this->data = [];
        $this->uploads = [];
        $this->nomor_antrean = null;
        $this->success = false;
        foreach ($this->formFields as $field) {
            $this->data[$field->name] = '';
        }
    }
};
?>

<div>
    <x-navbar />

    <section class="bg-neutral-50/60 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16">

            <a href="{{ route('pelayanan.index') }}" wire:navigate class="fade-up inline-flex items-center gap-1.5 text-sm text-neutral-500 hover:text-brand-600 transition-colors mb-6">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>

            @if($success)
                {{-- Success Screen --}}
                <div class="print-area fade-up rounded-2xl border border-neutral-200/60 bg-white p-8 sm:p-12 text-center shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)]">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 ring-1 ring-emerald-200/50">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Sukses</span>
                    <h1 class="mt-2 font-display text-2xl font-bold text-neutral-900">Pendaftaran Berhasil</h1>
                    <p class="mt-2 text-sm text-neutral-500">Simpan nomor antrean Anda untuk melacak status</p>

                    <div class="mt-8 inline-block rounded-2xl border-2 border-brand-200/60 bg-brand-50/50 px-8 py-5">
                        <p class="text-xs text-brand-600 font-medium uppercase tracking-wider">Nomor Antrean</p>
                        <p class="mt-1 text-3xl sm:text-4xl font-bold font-mono text-brand-700 tracking-wider">{{ $nomor_antrean }}</p>
                    </div>

                    <div class="mt-6 rounded-xl border border-neutral-100 bg-neutral-50/50 p-5 text-left text-sm text-neutral-500">
                        <p class="font-medium text-neutral-900 mb-2">Informasi Penting:</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-start gap-2">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Antrean Anda saat ini berstatus <strong class="text-brand-600">Menunggu</strong></span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Gunakan nomor antrean untuk melacak status secara real-time</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Harap datang sesuai dengan urutan antrean</span>
                            </li>
                        </ul>
                    </div>

                    <div class="no-print mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('tracking.show', $nomor_antrean) }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-xl bg-neutral-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-neutral-800">
                            Lacak Antrean
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <button @click="window.print()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-200 px-6 py-3 text-sm font-medium text-neutral-700 transition-all hover:bg-neutral-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak
                        </button>
                        <button wire:click="resetForm" class="inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-200 px-6 py-3 text-sm font-medium text-neutral-700 transition-all hover:bg-neutral-50">
                            Daftar Lagi
                        </button>
                    </div>
                </div>
            @else
                {{-- Form --}}
                <div class="fade-up mb-8">
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Pendaftaran</span>
                    <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">{{ $pelayanan->nama_pelayanan }}</h1>
                    <div class="mt-3 h-0.5 w-12 bg-neutral-200 rounded-full"></div>
                    <p class="mt-4 text-sm text-neutral-500">Isi data diri untuk mengambil antrean</p>
                </div>

                <div class="fade-up-scale rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-[0_4px_20px_-8px_rgba(0,0,0,0.06)]">
                    <form wire:submit="daftar" class="space-y-5">
                        @foreach($this->formFields as $field)
                            <div>
                                <label class="block text-sm font-medium text-neutral-900 mb-1.5">
                                    {{ $field->label }}
                                    @if($field->required)
                                        <span class="text-red-400">*</span>
                                    @endif
                                </label>

                                @php
                                    $errorKey = $field->isFileUpload() ? "uploads.{$field->name}" : "data.{$field->name}";
                                    $wireModel = $field->isFileUpload() ? "uploads.{$field->name}" : "data.{$field->name}";
                                @endphp

                                @switch($field->type)
                                    @case('textarea')
                                        <textarea
                                            wire:model="{{ $wireModel }}"
                                            placeholder="{{ $field->placeholder }}"
                                            rows="3"
                                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 shadow-sm transition-all focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-100"
                                        ></textarea>
                                        @break

                                    @case('select')
                                        <select
                                            wire:model="{{ $wireModel }}"
                                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition-all focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-100"
                                        >
                                            <option value="">{{ $field->placeholder ?: 'Pilih ' . $field->label }}</option>
                                            @foreach($field->options ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case('date')
                                        <input
                                            type="date"
                                            wire:model="{{ $wireModel }}"
                                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition-all focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-100"
                                        >
                                        @break

                                    @case('file')
                                    @case('image')
                                        <div class="relative">
                                            <input
                                                type="file"
                                                wire:model="{{ $wireModel }}"
                                                @if($field->type === 'image') accept="image/*" @endif
                                                class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-all cursor-pointer"
                                            >
                                        </div>
                                        @if($field->type === 'image' && !empty($uploads[$field->name]))
                                            <div class="mt-3">
                                                <img src="{{ $uploads[$field->name]->temporaryUrl() }}" class="h-24 w-24 rounded-xl object-cover border border-neutral-200" loading="lazy">
                                            </div>
                                        @endif
                                        @break

                                    @default
                                        <input
                                            type="{{ $field->type === 'email' ? 'email' : ($field->type === 'tel' ? 'tel' : ($field->type === 'number' ? 'number' : 'text')) }}"
                                            wire:model="{{ $wireModel }}"
                                            placeholder="{{ $field->placeholder }}"
                                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 shadow-sm transition-all focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-100"
                                        >
                                @endswitch

                                @if($field->help_text)
                                    <p class="mt-1.5 text-xs text-neutral-400">{{ $field->help_text }}</p>
                                @endif

                                @error($errorKey)
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="pt-4 border-t border-neutral-100">
                            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 active:scale-[0.99]">
                                Daftar
                            </button>
                            <p class="mt-3 text-xs text-center text-neutral-400">Dengan mendaftar, Anda menyetujui ketentuan yang berlaku</p>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>

    <x-footer />
</div>
