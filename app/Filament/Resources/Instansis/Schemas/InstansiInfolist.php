<?php

namespace App\Filament\Resources\Instansis\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstansiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail OPD')->columnSpanFull()->schema([
                SpatieMediaLibraryImageEntry::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->conversion('thumb')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_instansi) . '&background=d97706&color=fff'),
                TextEntry::make('kode_instansi')->label('Kode OPD'),
                TextEntry::make('nama_instansi')->label('Nama OPD'),
                TextEntry::make('deskripsi_layanan')->label('Deskripsi Layanan')->placeholder('-'),
                TextEntry::make('kecamatan')->label('Kecamatan')->placeholder('-'),
                TextEntry::make('tipe')->label('Tipe'),
                IconEntry::make('aktif')->boolean()->label('Aktif'),
                TextEntry::make('created_at')->dateTime()->label('Dibuat Pada'),
            ]),
        ]);
    }
}
