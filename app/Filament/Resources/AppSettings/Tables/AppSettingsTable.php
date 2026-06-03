<?php

namespace App\Filament\Resources\AppSettings\Tables;

use App\Models\AppSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("key")->label("Key")->searchable()->sortable(),
                SpatieMediaLibraryImageColumn::make("media")
                    ->label("Value")
                    ->collection(fn($record) => $record->key)
                    ->conversion("thumb"),
                TextColumn::make("value")->label("Text")->limit(50),
                TextColumn::make("group")->label("Grup")->badge()->sortable(),
                TextColumn::make("created_at")
                    ->label("Dibuat")
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
