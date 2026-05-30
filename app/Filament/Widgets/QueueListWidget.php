<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class QueueListWidget extends TableWidget
{
    protected ?string $pollingInterval = "10s";
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = "full";

    protected static ?string $heading = "Antrean Hari Ini";

    protected ?string $description = "Daftar antrean yang masuk hari ini";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::whereDate("created_at", today())
                    ->with("pelayanan")
                    ->orderBy("created_at"),
            )
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
                    ->color(
                        fn(StatusPendaftaran $state) => match ($state) {
                            StatusPendaftaran::Waiting => "warning",
                            StatusPendaftaran::Serving => "info",
                            StatusPendaftaran::Done => "success",
                            StatusPendaftaran::Skipped => "danger",
                        },
                    )
                    ->sortable(),
                TextColumn::make("created_at")
                    ->label("Waktu Daftar")
                    ->dateTime("H:i")
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make("serve")
                        ->label("Layani")
                        ->icon("heroicon-o-play")
                        ->color("info")
                        ->visible(
                            fn(Pendaftaran $record) => $record->status ===
                                StatusPendaftaran::Waiting,
                        )
                        ->action(
                            fn(Pendaftaran $record) => $record->update([
                                "status" => StatusPendaftaran::Serving,
                                "waktu_dilayani" => now(),
                            ]),
                        ),
                    Action::make("done")
                        ->label("Selesai")
                        ->icon("heroicon-o-check")
                        ->color("success")
                        ->visible(
                            fn(Pendaftaran $record) => $record->status ===
                                StatusPendaftaran::Serving,
                        )
                        ->action(
                            fn(Pendaftaran $record) => $record->update([
                                "status" => StatusPendaftaran::Done,
                                "waktu_selesai" => now(),
                            ]),
                        ),
                    Action::make("skip")
                        ->label("Lewati")
                        ->icon("heroicon-o-forward")
                        ->color("danger")
                        ->visible(
                            fn(Pendaftaran $record) => $record->status ===
                                StatusPendaftaran::Waiting,
                        )
                        ->action(
                            fn(Pendaftaran $record) => $record->update([
                                "status" => StatusPendaftaran::Skipped,
                            ]),
                        ),
                ]),
            ]);
    }
}
