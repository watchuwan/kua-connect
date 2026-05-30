<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Pengaturan')->columnSpanFull()->schema([
                SpatieMediaLibraryImageEntry::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->conversion('thumb')
                    ->hidden(fn ($record) => $record->key !== 'logo'),

                SpatieMediaLibraryImageEntry::make('hero_image')
                    ->label('Hero Image')
                    ->collection('hero_image')
                    ->conversion('thumb')
                    ->hidden(fn ($record) => $record->key !== 'hero_image'),

                TextEntry::make('key')->label('Key'),
                TextEntry::make('value')->label('Value')
                    ->hidden(fn ($record) => in_array($record->key, ['logo', 'hero_image'])),
                TextEntry::make('group')->label('Grup')->badge(),
                TextEntry::make('created_at')->dateTime()->label('Dibuat Pada'),
                TextEntry::make('updated_at')->dateTime()->label('Diperbarui Pada'),
            ]),
        ]);
    }
}
