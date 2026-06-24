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

    public bool $submitting = false;

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
        return 'Daftar ' . $this->pelayanan->nama_pelayanan . ' - KUA Connect';
    }

    public function rules(): array
    {
        $rules = [];
        foreach ($this->formFields as $field) {
            $fieldRules = $field->getValidationRule();
            if ($field->isFileUpload() && $field->isMultiple()) {
                $base = "uploads.{$field->name}";
                $rules[$base] = ['array'];
                if ($field->required) {
                    $rules[$base][] = 'min:1';
                }
                $rules["{$base}.*"] = array_filter($fieldRules, fn($r) => !in_array($r, ['required', 'nullable']));
            } elseif ($field->isFileUpload()) {
                $rules["uploads.{$field->name}"] = $fieldRules;
            } else {
                $rules["data.{$field->name}"] = $fieldRules;
            }
        }
        return $rules;
    }

    public function validationAttributes(): array
    {
        $attrs = [];
        foreach ($this->formFields as $field) {
            if ($field->isFileUpload() && $field->isMultiple()) {
                $attrs["uploads.{$field->name}"] = $field->label;
                $attrs["uploads.{$field->name}.*"] = $field->label;
            } elseif ($field->isFileUpload()) {
                $attrs["uploads.{$field->name}"] = $field->label;
            } else {
                $attrs["data.{$field->name}"] = $field->label;
            }
        }
        return $attrs;
    }

    public function daftar(): void
    {
        $this->submitting = true;
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
                    if ($field->isMultiple()) {
                        $filesInfo = [];
                        foreach ($this->uploads[$field->name] as $file) {
                            $filesInfo[] = [
                                'filename' => $file->getClientOriginalName(),
                                'size' => $file->getSize(),
                                'mime' => $file->getMimeType(),
                            ];
                        }
                        $submittedData[$field->name] = $filesInfo;
                    } else {
                        $submittedData[$field->name] = [
                            'filename' => $this->uploads[$field->name]->getClientOriginalName(),
                            'size' => $this->uploads[$field->name]->getSize(),
                            'mime' => $this->uploads[$field->name]->getMimeType(),
                        ];
                    }
                }
            } else {
                $submittedData[$field->name] = $this->data[$field->name] ?? '';
            }
        }

        $pendaftaran = Pendaftaran::create([
            'nomor_antrean' => $nomor,
            'pelayanan_id' => $this->pelayanan->id,
            'data' => $submittedData,
            'status' => \App\Enums\StatusPendaftaran::Pending->value,
        ]);

        foreach ($this->formFields as $field) {
            if ($field->isFileUpload() && !empty($this->uploads[$field->name])) {
                $files = $field->isMultiple() ? $this->uploads[$field->name] : [$this->uploads[$field->name]];
                foreach ($files as $file) {
                    $pendaftaran->addMedia($file->getRealPath())
                        ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('pendaftaran_files');
                }
            }
        }

        $this->nomor_antrean = $pendaftaran->nomor_antrean;
        $this->success = true;
        $this->submitting = false;
    }

    public function removeUploadedFile(string $fieldName, int $index): void
    {
        if (!isset($this->uploads[$fieldName][$index])) {
            return;
        }

        // Hapus item dan re-index tanpa reassign array baru — Livewire maintain referensi temp files
        array_splice($this->uploads[$fieldName], $index, 1);

        // Reset error untuk field ini agar border merah hilang
        $this->resetErrorBag("uploads.{$fieldName}");
        $this->resetErrorBag("uploads.{$fieldName}.*");
    }

    public function removeSingleUpload(string $fieldName): void
    {
        unset($this->uploads[$fieldName]);
        // Force set ke null agar Livewire detect perubahan state
        $this->uploads[$fieldName] = null;

        // Reset error untuk field ini
        $this->resetErrorBag("uploads.{$fieldName}");
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

    <section class="bg-gradient-to-b from-brand-50/20 to-white min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-16">

            <a href="{{ route('pelayanan.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-neutral-500 hover:text-brand-600 transition-colors mb-8">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>

            @if($success)
                {{-- Success Screen --}}
                <div class="text-center">
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 ring-1 ring-emerald-200/50">
                        <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Sukses</span>
                    <h1 class="mt-2 font-display text-2xl font-bold text-neutral-900">Pendaftaran Berhasil</h1>
                    <p class="mt-2 text-sm text-neutral-500">Simpan nomor antrean Anda untuk melacak status</p>

                    <div class="mt-8 inline-block rounded-2xl border-2 border-brand-200/60 bg-brand-50/50 px-10 py-6">
                        <p class="text-xs text-brand-600 font-medium uppercase tracking-wider">Nomor Antrean</p>
                        <p class="mt-1 text-3xl sm:text-4xl font-bold font-mono text-brand-700 tracking-wider">{{ $nomor_antrean }}</p>
                    </div>

                    <div class="mt-6 mx-auto max-w-md rounded-xl border border-neutral-100 bg-white p-5 text-left text-sm text-neutral-500 shadow-sm">
                        <p class="font-medium text-neutral-900 mb-3">Informasi Penting:</p>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-2.5">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Antrean Anda saat ini berstatus <strong class="text-brand-600">Pending</strong> — menunggu verifikasi petugas</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Gunakan nomor antrean untuk melacak status secara real-time</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="h-4 w-4 text-brand-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Petugas KUA akan menghubungi Anda untuk jadwal verifikasi</span>
                            </li>
                        </ul>
                    </div>

                    <div class="no-print mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('tracking.show', $nomor_antrean) }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-xl bg-neutral-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-neutral-800">
                            Lacak Antrean
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-200 px-6 py-3 text-sm font-medium text-neutral-700 transition-all hover:bg-neutral-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak
                        </button>
                        <button wire:click="resetForm" class="inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-200 px-6 py-3 text-sm font-medium text-neutral-700 transition-all hover:bg-neutral-50">
                            Daftar Lagi
                        </button>
                    </div>
                </div>
            @else
                {{-- Header --}}
                <div class="mb-8">
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600">Pendaftaran</span>
                    <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-neutral-900 sm:text-3xl">{{ $pelayanan->nama_pelayanan }}</h1>
                    <div class="mt-3 h-0.5 w-12 bg-brand-300 rounded-full"></div>
                    <p class="mt-3 text-sm text-neutral-500">Lengkapi data diri Anda untuk mengambil antrean</p>
                </div>

                {{-- Form --}}
                <div class="space-y-6">
                    <form wire:submit="daftar">
                        @php
                            $nonFileFields = $this->formFields->reject(fn($f) => $f->isFileUpload());
                            $fileFields = $this->formFields->filter(fn($f) => $f->isFileUpload());
                        @endphp

                        {{-- Validation summary --}}
                        @if($errors->any())
                            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-semibold">Mohon perbaiki kesalahan berikut:</span>
                                </div>
                                <ul class="list-disc list-inside space-y-0.5 text-red-600">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Data Pemohon section --}}
                        @if($nonFileFields->isNotEmpty())
                            <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-neutral-100">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-semibold text-neutral-900">Data Pemohon</h2>
                                        <p class="text-xs text-neutral-400">Isi data diri dengan benar</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                                    @foreach($nonFileFields as $field)
                                        @php
                                            $errorKey = "data.{$field->name}";
                                            $wireModel = "data.{$field->name}";
                                            $hasError = $errors->has($errorKey);
                                        @endphp

                                        <div class="{{ in_array($field->type, ['textarea']) ? 'sm:col-span-2' : '' }}">
                                            <label class="block text-sm font-medium mb-1.5 {{ $hasError ? 'text-red-600' : 'text-neutral-700' }}">
                                                {{ $field->label }}
                                                @if($field->required)
                                                    <span class="text-red-400">*</span>
                                                @endif
                                            </label>

                                            @switch($field->type)
                                                @case('textarea')
                                                    <textarea
                                                        wire:model="{{ $wireModel }}"
                                                        placeholder="{{ $field->placeholder }}"
                                                        rows="3"
                                                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 shadow-sm transition-all focus:outline-none focus:ring-2 {{ $hasError ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-neutral-200 bg-white focus:border-brand-300 focus:ring-brand-100' }}"
                                                    ></textarea>
                                                    @break

                                                @case('select')
                                                    <select
                                                        wire:model="{{ $wireModel }}"
                                                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 shadow-sm transition-all focus:outline-none focus:ring-2 {{ $hasError ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-neutral-200 bg-white focus:border-brand-300 focus:ring-brand-100' }}"
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
                                                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 shadow-sm transition-all focus:outline-none focus:ring-2 {{ $hasError ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-neutral-200 bg-white focus:border-brand-300 focus:ring-brand-100' }}"
                                                    >
                                                    @break

                                                @default
                                                    <input
                                                        type="{{ $field->type === 'email' ? 'email' : ($field->type === 'tel' ? 'tel' : ($field->type === 'number' ? 'number' : 'text')) }}"
                                                        wire:model="{{ $wireModel }}"
                                                        placeholder="{{ $field->placeholder }}"
                                                        class="w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 shadow-sm transition-all focus:outline-none focus:ring-2 {{ $hasError ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-neutral-200 bg-white focus:border-brand-300 focus:ring-brand-100' }}"
                                                    >
                                            @endswitch

                                            @if($field->help_text)
                                                <p class="mt-1 text-xs text-neutral-400">{{ $field->help_text }}</p>
                                            @endif

                                            @error($errorKey)
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Dokumen section --}}
                        @if($fileFields->isNotEmpty())
                            <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-neutral-100">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent-50 text-accent-600">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-semibold text-neutral-900">Dokumen Persyaratan</h2>
                                        <p class="text-xs text-neutral-400">Unggah dokumen yang diperlukan</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    @foreach($fileFields as $field)
                                        @php
                                            $errorKey = "uploads.{$field->name}";
                                            $wireModel = "uploads.{$field->name}";
                                            $hasError = $errors->has($errorKey) || $errors->has("{$errorKey}.*");
                                            $isImage = $field->type === 'image';
                                        @endphp

                                        @php
                                            $isMultiple = $field->isMultiple();
                                            // FIX: Gunakan !empty() dan is_array() untuk cek yang lebih akurat
                                            $hasFiles = $isMultiple
                                                ? (!empty($this->uploads[$field->name]) && is_array($this->uploads[$field->name]) && count($this->uploads[$field->name]) > 0)
                                                : (!empty($this->uploads[$field->name]));
                                        @endphp

                                        <div class="rounded-xl border-2 border-dashed p-5 transition-all {{ $hasError ? 'border-red-200 bg-red-50/30' : ($hasFiles ? 'border-brand-200 bg-brand-50/30' : 'border-neutral-200 bg-neutral-50/30 hover:border-brand-200 hover:bg-brand-50/20') }}">
                                            <label class="block text-sm font-medium mb-2 {{ $hasError ? 'text-red-600' : 'text-neutral-700' }}">
                                                {{ $field->label }}
                                                @if($field->required)
                                                    <span class="text-red-400">*</span>
                                                @endif
                                                @if($isMultiple)
                                                    <span class="text-xs text-neutral-400 font-normal">(bisa multiple)</span>
                                                @endif
                                            </label>

                                            <div class="flex items-center gap-4">
                                                <label class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm font-medium text-neutral-700 shadow-sm transition-all hover:bg-neutral-50 hover:border-neutral-300">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                    Pilih File
                                                    <input type="file" wire:model="{{ $wireModel }}" {{ $isMultiple ? 'multiple' : '' }} @if($isImage) accept="image/*" @endif class="hidden">
                                                </label>

                                                @if(!$isMultiple && !empty($this->uploads[$field->name]))
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="inline-flex items-center gap-1.5 text-sm text-neutral-600 truncate max-w-[200px]">
                                                            <svg class="h-4 w-4 shrink-0 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                            <span class="truncate">{{ $this->uploads[$field->name]->getClientOriginalName() }}</span>
                                                        </span>
                                                        <button type="button" wire:click="removeSingleUpload('{{ $field->name }}')" wire:loading.attr="disabled" wire:target="removeSingleUpload" class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow-sm hover:bg-red-600 shrink-0 transition-colors">
                                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($isMultiple && $hasFiles)
                                                <div class="mt-3 flex flex-wrap gap-3">
                                                    @foreach($this->uploads[$field->name] as $fi => $file)
                                                        <div wire:key="upload-{{ $field->name }}-{{ $fi }}-{{ $file->getFilename() }}" class="relative group">
                                                            @if($isImage)
                                                                <a href="{{ $file->temporaryUrl() }}" target="_blank" rel="noopener noreferrer">
                                                                    <img src="{{ $file->temporaryUrl() }}" class="h-24 w-24 rounded-lg object-cover border border-neutral-200 shadow-sm" loading="lazy">
                                                                </a>
                                                            @else
                                                                <div class="flex items-center gap-2 rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 text-sm text-neutral-700 shadow-sm max-w-[240px]">
                                                                    <svg class="h-4 w-4 shrink-0 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                    <span class="truncate">{{ $file->getClientOriginalName() }}</span>
                                                                </div>
                                                            @endif
                                                            <button type="button" wire:click="removeUploadedFile('{{ $field->name }}', {{ $fi }})" wire:loading.attr="disabled" wire:target="removeUploadedFile" class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow-sm hover:bg-red-600 transition-colors">
                                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif(!$isMultiple && $isImage && !empty($this->uploads[$field->name]))
                                                <div class="mt-3">
                                                    <div class="relative inline-block">
                                                        <a href="{{ $this->uploads[$field->name]->temporaryUrl() }}" target="_blank" rel="noopener noreferrer">
                                                            <img src="{{ $this->uploads[$field->name]->temporaryUrl() }}" class="h-28 w-28 rounded-lg object-cover border border-neutral-200 shadow-sm" loading="lazy">
                                                        </a>
                                                        <button type="button" wire:click="removeSingleUpload('{{ $field->name }}')" wire:loading.attr="disabled" wire:target="removeSingleUpload" class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow-sm hover:bg-red-600 transition-colors">
                                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($field->help_text)
                                                <p class="mt-1.5 text-xs text-neutral-400">{{ $field->help_text }}</p>
                                            @endif

                                            @error("{$errorKey}.*")
                                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                            @error($errorKey)
                                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                            @enderror

                                            <div wire:loading wire:target="{{ $wireModel }}" class="mt-2 flex items-center gap-2 text-xs text-brand-600">
                                                <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                Mengunggah...
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Submit --}}
                        <div class="rounded-2xl border border-neutral-200/60 bg-white p-6 sm:p-8 shadow-sm">
                            <button type="submit" wire:loading.attr="disabled" class="relative w-full rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 active:scale-[0.99] disabled:opacity-60 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="daftar">Daftar Sekarang</span>
                                <span wire:loading wire:target="daftar" class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Memproses...
                                </span>
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
