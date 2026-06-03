<?php

namespace App\Filament\Resources\Instansis\Schemas;

use App\Enums\TipeInstansi;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InstansiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Data OPD")
                ->columnSpanFull()
                ->schema([
                    SpatieMediaLibraryFileUpload::make("logo")
                        ->label("Logo")
                        ->disk("public")
                        ->collection("logo")
                        ->conversion("thumb")
                        ->image()
                        ->imageResizeMode("cover")
                        ->imageCropAspectRatio("1:1")
                        ->circleCropper()
                        ->maxSize(2048),
                    TextInput::make("kode_instansi")
                        ->label("Kode OPD")
                        ->required(),
                    TextInput::make("nama_instansi")
                        ->label("Nama OPD")
                        ->required(),
                    Textarea::make("deskripsi_layanan")->label(
                        "Deskripsi Layanan",
                    ),
                    TextInput::make("kecamatan")->label("Kecamatan"),
                    Select::make("tipe")
                        ->label("Tipe")
                        ->options(TipeInstansi::class)
                        ->required(),
                    Toggle::make("aktif")
                        ->label("Aktif")
                        ->default(true)
                        ->inline(false),
                ]),
        ]);
    }
}
