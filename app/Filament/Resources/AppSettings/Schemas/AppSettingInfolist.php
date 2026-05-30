<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AppSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('key')->label('Key'),
            TextEntry::make('value')->label('Value'),
            TextEntry::make('group')->label('Grup')->badge(),
            TextEntry::make('created_at')->dateTime()->label('Dibuat Pada'),
            TextEntry::make('updated_at')->dateTime()->label('Diperbarui Pada'),
        ]);
    }
}
