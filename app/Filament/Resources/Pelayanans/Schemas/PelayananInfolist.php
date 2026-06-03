<?php

namespace App\Filament\Resources\Pelayanans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PelayananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Data Pelayanan")
                ->columns(6)
                ->columnSpanFull()
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->schema([
                    SpatieMediaLibraryImageEntry::make("icon")
                        ->label("Icon")
                        ->collection("icon")
                        ->conversion("thumb")
                        ->circular()
                        ->defaultImageUrl(
                            fn($record) => "https://ui-avatars.com/api/?name=" .
                                urlencode($record->nama_pelayanan) .
                                "&background=d97706&color=fff&size=80",
                        ),
                    TextEntry::make("nama_pelayanan")->label("Nama Pelayanan"),
                    TextEntry::make("slug")->label("Slug"),
                    IconEntry::make("aktif")->boolean()->label("Aktif"),
                    TextEntry::make("formFields_count")
                        ->label("Jumlah Field")
                        ->counts("formFields"),
                    TextEntry::make("pendaftaran_count")
                        ->label("Total Antrean")
                        ->counts("pendaftaran"),
                ]),
        ]);
    }
}
