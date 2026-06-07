<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pengguna')->columnSpanFull()->schema([
                    SpatieMediaLibraryImageEntry::make('avatar')
                        ->label('Foto Profil')
                        ->collection('avatar')
                        ->conversion('thumb')
                        ->circular()
                        ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=d97706&color=fff'),
                    TextEntry::make('name'),
                    TextEntry::make('email')
                        ->label('Email address'),
                    TextEntry::make('email_verified_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('updated_at')
                        ->dateTime()
                        ->placeholder('-'),
                ]),
            ]);
    }
}
