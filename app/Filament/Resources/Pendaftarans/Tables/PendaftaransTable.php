<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use App\Enums\StatusPendaftaran;
use App\Filament\Resources\Pendaftarans\Actions\TransisiStatusAction;
use App\Models\Pendaftaran;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nomor_antrean")
                    ->label("No. Antrean")
                    ->searchable()
                    ->sortable(),
                TextColumn::make("pelayanan.nama_pelayanan")
                    ->label("Pelayanan")
                    ->sortable(),
                TextColumn::make("status")
                    ->label("Status")
                    ->badge()
                    ->color(fn(StatusPendaftaran $state) => $state->getColor())
                    ->icon(fn(StatusPendaftaran $state) => $state->getIcon())
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make("media")
                    ->label("File")
                    ->collection("pendaftaran_files")
                    ->conversion("thumb")
                    ->limit(3)
                    ->square()
                    ->stacked(),
                TextColumn::make("no_surat")
                    ->label("No. Surat")
                    ->searchable()
                    ->placeholder("-"),
                TextColumn::make("created_at")
                    ->label("Waktu Daftar")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make("status")->options(StatusPendaftaran::class),
                SelectFilter::make("pelayanan_id")
                    ->label("Pelayanan")
                    ->relationship("pelayanan", "nama_pelayanan"),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make(
                    TransisiStatusAction::getAllTransitionActions(),
                )
                    ->label("Proses")
                    ->icon("heroicon-o-chevron-up-down")
                    ->button()
                    ->color("warning"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

}
