<?php

namespace App\Filament\Resources\Visitors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VisitorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Kunjungan')->columnSpanFull()->schema([
                TextEntry::make('ip_address')->label('IP Address'),
                TextEntry::make('user_agent')->label('User Agent'),
                TextEntry::make('page')->label('Halaman'),
                TextEntry::make('created_at')->dateTime()->label('Waktu Kunjungan'),
            ]),
        ]);
    }
}
