<?php

namespace App\Filament\Resources\Pelayanans\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PelayananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Data Pelayanan")
                ->columnSpanFull()
                ->schema([
                    SpatieMediaLibraryFileUpload::make('icon')
                        ->label('Icon')
                        ->disk('public')
                        ->collection('icon')
                        ->conversion('thumb')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->maxSize(1024),
                    TextInput::make("nama_pelayanan")
                        ->label("Nama Pelayanan")
                        ->required(),
                    Toggle::make("aktif")
                        ->label("Aktif")
                        ->default(true)
                        ->inline(false),
                ]),
            Section::make("Field Formulir")
                ->columnSpanFull()
                ->schema([
                    Repeater::make("formFields")
                        ->relationship("formFields")
                        ->orderColumn("order")
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make("name")
                                    ->label("Nama Field (key)")
                                    ->required()
                                    ->placeholder("nik"),
                                TextInput::make("label")
                                    ->label("Label Tampilan")
                                    ->required()
                                    ->placeholder("NIK"),
                            ]),
                            Grid::make(2)->schema([
                                Select::make("type")
                                    ->label("Tipe Input")
                                    ->options([
                                        "text" => "Teks",
                                        "email" => "Email",
                                        "tel" => "Telepon",
                                        "number" => "Angka",
                                        "textarea" => "Teks Panjang",
                                        "select" => "Dropdown",
                                        "date" => "Tanggal",
                                        "file" => "File",
                                        "image" => "Gambar",
                                    ])
                                    ->default("text")
                                    ->required()
                                    ->live(),
                                Toggle::make("required")
                                    ->label("Wajib")
                                    ->inline(false)
                                    ->default(false),
                            ]),
                            Textarea::make("options")
                                ->label("Pilihan (satu per baris)")
                                ->rows(3)
                                ->visible(fn($get) => $get("type") === "select")
                                ->formatStateUsing(
                                    fn($state) => is_array($state) &&
                                    array_is_list($state)
                                        ? implode("\n", $state)
                                        : $state,
                                )
                                ->dehydrateStateUsing(
                                    fn($state) => array_filter(
                                        array_map(
                                            "trim",
                                            explode("\n", $state ?? ""),
                                        ),
                                    ),
                                ),
                            Grid::make(2)
                                ->visible(
                                    fn($get) => in_array($get("type"), [
                                        "file",
                                        "image",
                                    ]),
                                )
                                ->schema([
                                    TextInput::make("max_size")
                                        ->label("Max Size (KB)")
                                        ->numeric()
                                        ->default(2048)
                                        ->helperText(
                                            "Batas maksimal ukuran file dalam KB",
                                        ),
                                    TextInput::make("mimes")
                                        ->label("Format File")
                                        ->placeholder("pdf,jpg,png")
                                        ->helperText(
                                            "Format yang diizinkan (pisahkan koma)",
                                        ),
                                ]),
                            TextInput::make("placeholder")
                                ->label("Placeholder")
                                ->helperText("ini placeholder")
                                ->visible(
                                    fn($get) => !in_array($get("type"), [
                                        "select",
                                        "date",
                                        "file",
                                        "image",
                                        "textarea",
                                    ]),
                                ),
                            Textarea::make("help_text")
                                ->label("Teks Bantuan")
                                ->rows(2),
                            Grid::make(2)->schema([
                                Toggle::make("active")
                                    ->label("Aktif")
                                    ->default(true)
                                    ->inline(false),
                            ]),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(function (
                            array $data,
                        ): array {
                            if (
                                in_array($data["type"] ?? "", ["file", "image"])
                            ) {
                                $data["options"] = [
                                    "max_size" =>
                                        (int) ($data["max_size"] ?? 2048),
                                    "mimes" => $data["mimes"] ?? "",
                                ];
                            }
                            unset($data["max_size"], $data["mimes"]);
                            return $data;
                        })
                        ->mutateRelationshipDataBeforeSaveUsing(function (
                            array $data,
                            $record,
                        ): array {
                            if (
                                in_array($data["type"] ?? "", ["file", "image"])
                            ) {
                                $data["options"] = [
                                    "max_size" =>
                                        (int) ($data["max_size"] ?? 2048),
                                    "mimes" => $data["mimes"] ?? "",
                                ];
                            }
                            unset($data["max_size"], $data["mimes"]);
                            return $data;
                        })
                        ->mutateRelationshipDataBeforeFillUsing(function (
                            array $data,
                        ): array {
                            if (
                                in_array($data["type"] ?? "", ["file", "image"])
                            ) {
                                $options = $data["options"] ?? [];
                                $data["max_size"] = is_array($options)
                                    ? $options["max_size"] ?? 2048
                                    : 2048;
                                $data["mimes"] = is_array($options)
                                    ? $options["mimes"] ?? ""
                                    : "";
                            }
                            return $data;
                        }),
                ]),
        ]);
    }
}
