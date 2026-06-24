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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PendaftaranInfolist
{
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
                KeyValueEntry::make('data')->label('Data Pemohon')
                    ->state(fn (Pendaftaran $record): array => is_array($record->data)
                        ? collect($record->data)->map(fn ($v) => is_array($v) ? json_encode($v) : $v)->all()
                        : []),
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
                        ->map(fn ($log) => '[' . $log->created_at->format('d/m/Y H:i') . '] '
                            . $log->description)
                        ->toArray()),
            ]),
        ]);
    }
}
