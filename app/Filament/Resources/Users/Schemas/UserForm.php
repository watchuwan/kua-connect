<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // SpatieMediaLibraryFileUpload::make('avatar')
            //     ->label('Foto Profil')
            //     ->collection('avatar')
            //     ->conversion('thumb')
            //     ->image()
            //     ->imageResizeMode('cover')
            //     ->imageCropAspectRatio('1:1')
            //     ->circleCropper()
            //     ->maxSize(1024),
            TextInput::make("name")->label("Nama")->required(),
            TextInput::make("email")
                ->label("Alamat Email")
                ->email()
                ->required(),
            Select::make("instansi_id")
                ->label("Instansi")
                ->relationship("instansi", "nama_instansi")
                ->searchable()
                ->preload(),
            Select::make("roles")
                ->label("Peran")
                ->relationship("roles", "name")
                ->preload()
                ->searchable(),
            DateTimePicker::make("email_verified_at")
                ->default(now())
                ->readOnly(),
            TextInput::make("password")->password()->required(),
            Toggle::make("is_active")->inline(false)->required(),
        ]);
    }
}
