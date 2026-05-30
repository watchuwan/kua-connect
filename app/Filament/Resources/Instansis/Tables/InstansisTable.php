<?php

namespace App\Filament\Resources\Instansis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InstansisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_instansi) . '&background=d97706&color=fff&size=40')
                    ->size(40),
                TextColumn::make('kode_instansi')->label('Kode OPD')->searchable()->sortable(),
                TextColumn::make('nama_instansi')->label('Nama OPD')->searchable()->sortable(),
                TextColumn::make('kecamatan')->label('Kecamatan')->searchable()->sortable(),
                TextColumn::make('tipe')->label('Tipe')->sortable()
                    ->badge()
                    ->color(fn (\App\Enums\TipeInstansi $state) => match ($state) {
                        \App\Enums\TipeInstansi::Dinas => Color::Blue,
                        \App\Enums\TipeInstansi::Badan => Color::Purple,
                        \App\Enums\TipeInstansi::Kecamatan => Color::Orange,
                        \App\Enums\TipeInstansi::Kelurahan => Color::Emerald,
                        default => Color::Gray,
                    })
                    ->formatStateUsing(fn (\App\Enums\TipeInstansi $state) => $state->value),
                IconColumn::make('aktif')->boolean()->label('Aktif'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
