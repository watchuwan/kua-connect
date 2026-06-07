<?php

namespace App\Filament\Resources\Penghulus\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenghuluInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Penghulu')->columnSpanFull()->schema([
                TextEntry::make('nama')->label('Nama Lengkap'),
                TextEntry::make('nip')->label('NIP')->placeholder('-'),
                TextEntry::make('no_hp')->label('No. HP')->placeholder('-'),
                TextEntry::make('aktif')->label('Status')->badge()
                    ->state(fn ($record) => $record->aktif ? 'Aktif' : 'Tidak Aktif')
                    ->color(fn ($state) => $state === 'Aktif' ? 'success' : 'danger'),
                TextEntry::make('created_at')->label('Dibuat Pada')->dateTime(),
            ]),
        ]);
    }
}
