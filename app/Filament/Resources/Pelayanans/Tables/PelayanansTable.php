<?php

namespace App\Filament\Resources\Pelayanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PelayanansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('icon')
                    ->label('Icon')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->nama_pelayanan) . '&background=d97706&color=fff&size=40')
                    ->size(40),
                TextColumn::make('nama_pelayanan')
                    ->label('Nama Pelayanan')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedClipboardDocumentList),
                TextColumn::make('formFields_count')
                    ->label('Fields')
                    ->counts('formFields')
                    ->badge()
                    ->color(Color::Blue)
                    ->alignCenter(),
                TextColumn::make('pendaftaran_count')
                    ->label('Antrean')
                    ->counts('pendaftaran')
                    ->badge()
                    ->color(fn($state) => $state > 0 ? Color::Green : Color::Gray)
                    ->alignCenter(),
                IconColumn::make('aktif')
                    ->boolean()
                    ->label('Aktif')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
