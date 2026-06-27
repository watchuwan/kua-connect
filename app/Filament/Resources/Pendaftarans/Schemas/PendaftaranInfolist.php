<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PendaftaranInfolist
{
    public static function renderDataHtml(Pendaftaran $record): string
    {
        $data = $record->data;
        if (!is_array($data) || empty($data)) {
            return '<p class="text-gray-400 dark:text-gray-500 text-sm">-</p>';
        }

        $record->load('media');

        $html = '<dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">';

        foreach ($data as $key => $value) {
            $label = Str::of($key)->headline();

            $html .= '<div class="space-y-1">';
            $html .= '<dt class="text-xs font-medium text-gray-950 dark:text-white uppercase tracking-wider">' . e($label) . '</dt>';

            if (is_string($value) && !blank($value)) {
                $html .= '<dd class="text-sm text-gray-950 dark:text-white">' . e($value) . '</dd>';
            } elseif (blank($value)) {
                $html .= '<dd class="text-sm text-gray-400 dark:text-gray-500 italic">-</dd>';
            } elseif (is_array($value)) {
                $items = isset($value['filename']) ? [$value] : $value;
                $html .= '<dd class="space-y-2">';
                foreach ($items as $item) {
                    if (!is_array($item) || !isset($item['filename'])) {
                        $html .= '<span class="text-sm text-gray-950 dark:text-white">' . e(json_encode($item)) . '</span>';
                        continue;
                    }
                    $html .= static::renderFileItemHtml($record, $item);
                }
                $html .= '</dd>';
            } else {
                $html .= '<dd class="text-sm text-gray-950 dark:text-white">' . e((string) $value) . '</dd>';
            }

            $html .= '</div>';
        }

        $html .= '</dl>';

        return $html;
    }

    protected static function renderFileItemHtml(Pendaftaran $record, array $item): string
    {
        $filename = $item['filename'] ?? 'Unknown';
        $mime = $item['mime'] ?? '';
        $isImage = str_starts_with($mime, 'image/');

        $media = $record->getRelationValue('media')
            ?->first(fn (Media $m) => $m->collection_name === 'pendaftaran_files' && $m->file_name === $filename);

        if (!$media) {
            return '<span class="text-sm text-gray-500 dark:text-gray-400">' . e($filename) . '</span>';
        }

        $url = $media->getUrl();

        if ($isImage) {
            $thumbUrl = $media->getAvailableUrl(['thumb']);
            return '<a href="' . e($url) . '" target="_blank" class="group inline-block">
                <img src="' . e($thumbUrl) . '" class="h-20 w-20 rounded-lg object-cover border border-gray-200 dark:border-gray-600 shadow-sm group-hover:shadow-md transition-shadow" loading="lazy">
                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block truncate max-w-[160px]">' . e($filename) . '</span>
            </a>';
        }

        return '<a href="' . e($url) . '" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 hover:underline">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            ' . e($filename) . '
        </a>';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Pendaftaran')->columnSpanFull()->schema([
                TextEntry::make('nomor_antrean')->label('Nomor Antrean'),
                TextEntry::make('pelayanan.nama_pelayanan')->label('Pelayanan'),
                TextEntry::make('status')->label('Status')->badge()
                    ->color(fn (StatusPendaftaran $state) => $state->getColor())
                    ->icon(fn (StatusPendaftaran $state) => $state->getIcon()),
                TextEntry::make('catatan')->label('Catatan Admin')->placeholder('-'),
                TextEntry::make('penghulu.nama')->label('Penghulu Bertugas')
                    ->placeholder('-')
                    ->visible(fn (Pendaftaran $record): bool => $record->pelayanan?->slug === 'pendaftaran-nikah'),
                TextEntry::make('no_surat')->label('Nomor Surat / Dokumen')->placeholder('-'),
                TextEntry::make('jadwal_kedatangan')->label('Jadwal Kedatangan')->dateTime()->placeholder('-'),
                TextEntry::make('derajat_kiblat')->label('Derajat Kiblat')
                    ->placeholder('-')
                    ->visible(fn (Pendaftaran $record): bool => $record->pelayanan?->slug === 'kalibrasi-arah-kiblat'),
                SpatieMediaLibraryImageEntry::make('media')
                    ->label('Gambar')
                    ->collection('pendaftaran_files')
                    ->conversion('thumb')
                    ->columns(3)
                    ->filterMediaUsing(fn (Collection $media): Collection => $media->filter(
                        fn (Media $m): bool => str_starts_with($m->mime_type, 'image/'),
                    )),
                TextEntry::make('fileNames')
                    ->label('File')
                    ->state(fn (Pendaftaran $record): array => $record->getMedia('pendaftaran_files')
                        ->filter(fn (Media $m): bool => !str_starts_with($m->mime_type, 'image/'))
                        ->map(fn (Media $m): string => $m->name . '.' . $m->extension)
                        ->toArray())
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->placeholder('-'),
                TextEntry::make('data_display')
                    ->label('Data Pemohon')
                    ->html()
                    ->state(fn (Pendaftaran $record): string => static::renderDataHtml($record))
                    ->columnSpanFull(),
                TextEntry::make('waktu_dilayani')->dateTime()->label('Waktu Dilayani')->placeholder('-'),
                TextEntry::make('waktu_selesai')->dateTime()->label('Waktu Selesai')->placeholder('-'),
                TextEntry::make('created_at')->dateTime()->label('Dibuat Pada'),
            ]),

            Section::make('Riwayat Aktivitas')->columnSpanFull()->schema([
                TextEntry::make('activityLogs')
                    ->label('Aktivitas')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->state(fn (Pendaftaran $record): array => $record->activityLogs()
                        ->orderByDesc('created_at')
                        ->get()
                        ->map(fn ($log) => '[' . $log->created_at->setTimezone('Asia/Jayapura')->format('d/m/Y H:i') . '] '
                            . $log->description)
                        ->toArray()),
            ]),
        ]);
    }
}
