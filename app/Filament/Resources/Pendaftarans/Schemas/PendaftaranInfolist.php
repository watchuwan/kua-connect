<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use App\Enums\StatusPendaftaran;
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
                    ->color(fn (StatusPendaftaran $state) => match ($state) {
                        StatusPendaftaran::Waiting => 'warning',
                        StatusPendaftaran::Serving => 'info',
                        StatusPendaftaran::Done => 'success',
                        StatusPendaftaran::Skipped => 'danger',
                    }),
                SpatieMediaLibraryImageEntry::make('media')
                    ->label('File Upload')
                    ->collection('pendaftaran_files')
                    ->conversion('thumb')
                    ->columns(3),
                KeyValueEntry::make('data')->label('Data Pemohon'),
                TextEntry::make('waktu_dilayani')->dateTime()->label('Waktu Dilayani')->placeholder('-'),
                TextEntry::make('waktu_selesai')->dateTime()->label('Waktu Selesai')->placeholder('-'),
                TextEntry::make('created_at')->dateTime()->label('Dibuat Pada'),
            ]),
        ]);
    }
}
