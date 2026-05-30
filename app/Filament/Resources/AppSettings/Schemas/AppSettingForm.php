<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pengaturan Aplikasi')->columnSpanFull()->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(),

                Select::make('value_type')
                    ->label('Tipe Value')
                    ->options([
                        'text' => 'Teks',
                        'image' => 'File (Gambar)',
                    ])
                    ->default(fn ($record) => in_array($record?->key ?? '', ['logo', 'hero_image']) ? 'image' : 'text')
                    ->live(),

                SpatieMediaLibraryFileUpload::make('media')
                    ->label('File')
                    ->collection(fn ($get) => $get('key') ?: 'general')
                    ->image()
                    ->maxSize(4096)
                    ->visible(fn ($get) => $get('value_type') === 'image'),

                Textarea::make('value')
                    ->label('Value')
                    ->visible(fn ($get) => $get('value_type') !== 'image'),

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
