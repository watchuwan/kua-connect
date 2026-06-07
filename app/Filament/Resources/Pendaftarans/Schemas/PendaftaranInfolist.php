<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                    ->label('File Terupload')
                    ->collection('pendaftaran_files')
                    ->conversion('thumb')
                    ->columns(3),
                KeyValueEntry::make('data')->label('Data Pemohon'),
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
