<x-filament-panels::page>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold tracking-tight">Cetak Pendaftaran</h1>
            <div class="flex gap-3 no-print">
                <button onclick="window.print()" class="filament-button filament-button-size-md inline-flex items-center justify-center gap-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 shadow-sm transition hover:bg-gray-50 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <x-filament::icon icon="heroicon-o-printer" class="h-5 w-5" />
                    Cetak
                </button>
                <a href="{{ \App\Filament\Resources\Pendaftarans\PendaftaranResource::getUrl('index') }}" class="filament-button filament-button-size-md inline-flex items-center justify-center gap-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-900 shadow-sm transition hover:bg-gray-50 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 no-print">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="no-print">
        <form wire:submit="render" class="mb-4 flex flex-wrap items-end gap-4 rounded-xl bg-white p-4 shadow-sm dark:bg-gray-800">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                <input type="date" wire:model.live="tanggal_awal" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                <input type="date" wire:model.live="tanggal_akhir" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
            </div>
        </form>
    </div>

    @php
        $grouped = $pendaftaran->groupBy(fn ($p) => $p->created_at->translatedFormat('l, d F Y'));
    @endphp

    @forelse ($grouped as $tanggal => $items)
        <div class="mb-6 break-inside-avoid rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ $tanggal }}</h2>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">No</th>
                        <th class="py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">No. Antrean</th>
                        <th class="py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">Nama</th>
                        <th class="py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">Pelayanan</th>
                        <th class="py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">OPD</th>
                        <th class="py-2 font-medium text-gray-500 dark:text-gray-400">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $i => $p)
                        <tr class="border-b border-gray-100 dark:border-gray-700/50">
                            <td class="py-2 pr-4 text-gray-900 dark:text-gray-200">{{ $loop->iteration }}</td>
                            <td class="py-2 pr-4 font-medium text-gray-900 dark:text-white">{{ $p->nomor_antrean }}</td>
                            <td class="py-2 pr-4 text-gray-900 dark:text-gray-200">{{ $p->nama ?? '-' }}</td>
                            <td class="py-2 pr-4 text-gray-900 dark:text-gray-200">{{ $p->pelayanan->nama_pelayanan ?? '-' }}</td>
                            <td class="py-2 pr-4 text-gray-900 dark:text-gray-200">{{ $p->pelayanan->instansi->nama_instansi ?? '-' }}</td>
                            <td class="py-2 text-gray-900 dark:text-gray-200">{{ $p->waktu_selesai?->format('H:i') ?? $p->updated_at->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-2 text-right text-sm text-gray-500 dark:text-gray-400">
                Total: {{ $items->count() }} pendaftaran
            </div>
        </div>
    @empty
        <div class="rounded-xl bg-white p-12 text-center shadow-sm dark:bg-gray-800">
            <x-filament::icon icon="heroicon-o-document-text" class="mx-auto mb-3 h-12 w-12 text-gray-400" />
            <p class="text-gray-500 dark:text-gray-400">Belum ada pendaftaran dengan status selesai.</p>
        </div>
    @endforelse

    <div class="no-print">
        {{ $pendaftaran->links() }}
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .break-inside-avoid { break-inside: avoid; }
        }
    </style>
</x-filament-panels::page>
