<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengaturan Aplikasi')->schema([
                TextInput::make('key')->label('Key')->required()->unique(ignoreRecord: true),
                Textarea::make('value')->label('Value'),
                Select::make('group')->label('Grup')->options([
                    'general' => 'General',
                    'social' => 'Social Media',
                    'links' => 'Links',
                    'contact' => 'Kontak',
                ])->default('general'),
            ]),
        ]);
    }
}
