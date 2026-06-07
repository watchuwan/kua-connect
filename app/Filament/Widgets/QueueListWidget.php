<?php

namespace App\Filament\Widgets;

use App\Enums\StatusPendaftaran;
use App\Filament\Resources\Pendaftarans\Actions\TransisiStatusAction;
use App\Models\Pendaftaran;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
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
                    ->color(fn (StatusPendaftaran $state) => $state->getColor())
                    ->icon(fn (StatusPendaftaran $state) => $state->getIcon())
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make("media")
                    ->label("File")
                    ->collection("pendaftaran_files")
                    ->conversion("thumb")
                    ->limit(3)
                    ->square()
                    ->stacked(),
                TextColumn::make("created_at")
                    ->label("Waktu Daftar")
                    ->dateTime("H:i")
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make(
                    TransisiStatusAction::getAllTransitionActions(),
                )
                    ->label('Proses')
                    ->icon('heroicon-m-arrow-right-circle'),
            ]);
    }

}
