<?php

namespace App\Filament\Resources\Penghulus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PenghuluForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Penghulu')->columnSpanFull()->schema([
                TextInput::make('nama')->label('Nama Lengkap')->required()->maxLength(255),
                TextInput::make('nip')->label('NIP')->nullable()->maxLength(30)->unique(ignoreRecord: true),
                TextInput::make('no_hp')->label('No. HP')->nullable()->tel()->maxLength(20),
                Toggle::make('aktif')->label('Aktif')->default(true),
            ]),
        ]);
    }
}
