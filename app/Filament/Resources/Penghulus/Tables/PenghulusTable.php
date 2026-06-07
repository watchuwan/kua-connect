<?php

namespace App\Filament\Resources\Penghulus\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PenghulusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                TextColumn::make('nip')->label('NIP')->searchable()->placeholder('-'),
                TextColumn::make('no_hp')->label('No. HP')->placeholder('-'),
                ToggleColumn::make('aktif')->label('Aktif'),
            ])
            ->defaultSort('nama');
    }
}
