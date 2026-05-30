<?php

namespace App\Filament\Resources\Pendaftarans\Tables;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('serve')
                        ->label('Layani')
                        ->icon('heroicon-o-play')
                        ->color('info')
                        ->visible(fn (Pendaftaran $record) => $record->status === StatusPendaftaran::Waiting)
                        ->action(fn (Pendaftaran $record) => $record->update([
                            'status' => StatusPendaftaran::Serving,
                            'waktu_dilayani' => now(),
                        ])),
                    Action::make('done')
                        ->label('Selesai')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->visible(fn (Pendaftaran $record) => $record->status === StatusPendaftaran::Serving)
                        ->action(fn (Pendaftaran $record) => $record->update([
                            'status' => StatusPendaftaran::Done,
                            'waktu_selesai' => now(),
                        ])),
                    Action::make('skip')
                        ->label('Lewati')
                        ->icon('heroicon-o-forward')
                        ->color('danger')
                        ->visible(fn (Pendaftaran $record) => $record->status === StatusPendaftaran::Waiting)
                        ->action(fn (Pendaftaran $record) => $record->update([
                            'status' => StatusPendaftaran::Skipped,
                        ])),
                ])
                    ->label('Ubah Status')
                    ->icon('heroicon-o-chevron-up-down')
                    ->button()
                    ->color('warning'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
