<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use App\Enums\StatusPendaftaran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_antrean')->label('No. Antrean')->searchable()->sortable(),
                TextColumn::make('pelayanan.nama_pelayanan')->label('Pelayanan')->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (StatusPendaftaran $state) => match ($state) {
                        StatusPendaftaran::Waiting => 'warning',
                        StatusPendaftaran::Serving => 'info',
                        StatusPendaftaran::Done => 'success',
                        StatusPendaftaran::Skipped => 'danger',
                    })->sortable(),
                TextColumn::make('created_at')->label('Waktu Daftar')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(StatusPendaftaran::class),
                SelectFilter::make('pelayanan_id')->label('Pelayanan')->relationship('pelayanan', 'nama_pelayanan'),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
