<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Models\ActivityLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('event')->label('Event')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'status_updated' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'created' => 'Dibuat',
                        'status_updated' => 'Status Berubah',
                        default => $state,
                    }),
                TextColumn::make('description')->label('Deskripsi')->limit(60),
                TextColumn::make('causer.name')->label('Oleh')->placeholder('Sistem'),
                TextColumn::make('subject.nomor_antrean')->label('No. Antrean')->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Dibuat',
                        'status_updated' => 'Status Berubah',
                    ]),
                SelectFilter::make('log_name')
                    ->label('Jenis')
                    ->options([
                        'pendaftaran' => 'Pendaftaran',
                    ]),
            ]);
    }
}
