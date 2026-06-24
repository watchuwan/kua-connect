<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use App\Enums\StatusPendaftaran;
use App\Models\FormField;
use App\Models\Pelayanan;
use App\Models\Penghulu;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PendaftaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pendaftaran')->columnSpanFull()->schema([
                Select::make('pelayanan_id')->label('Pelayanan')
                    ->relationship('pelayanan', 'nama_pelayanan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live(),
                Select::make('status')->label('Status')
                    ->options(fn (Get $get): array => static::getStatusOptions($get))
                    ->visible(fn (string $operation): bool => $operation !== 'create')
                    ->required(),
            ]),

            Section::make('Data Pemohon')
                ->columnSpanFull()
                ->schema(fn (Get $get, ?array $state) => static::buildDynamicFields($get('pelayanan_id'))),

            Section::make('Catatan & Dokumen')->columnSpanFull()->schema([
                Textarea::make('catatan')->label('Catatan Admin')
                    ->helperText('Alasan revisi, instruksi, atau keterangan lainnya')
                    ->columnSpanFull(),
                TextInput::make('no_surat')->label('Nomor Surat / Dokumen Resmi')
                    ->helperText('Nomor AIW, Rekomendasi, SK Mualaf, atau Sertifikat Kiblat')
                    ->columnSpanFull(),
                Select::make('penghulu_id')->label('Penghulu Bertugas')
                    ->relationship('penghulu', 'nama')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->visible(fn (Get $get): bool => static::isNikahService($get)),
                DateTimePicker::make('jadwal_kedatangan')->label('Jadwal Kedatangan'),
                TextInput::make('derajat_kiblat')->label('Derajat Arah Kiblat')
                    ->visible(fn (Get $get): bool => static::isKiblatService($get))
                    ->numeric()
                    ->suffix('°'),
            ]),

            Section::make('Waktu')->columnSpanFull()->schema([
                DateTimePicker::make('waktu_dilayani')->label('Waktu Dilayani'),
                DateTimePicker::make('waktu_selesai')->label('Waktu Selesai'),
            ]),
        ]);
    }

    protected static function getStatusOptions(Get $get): array
    {
        $serviceId = $get('pelayanan_id');
        $service = $serviceId ? Pelayanan::find($serviceId) : null;
        $slug = $service?->slug;

        if (!$slug) {
            return StatusPendaftaran::class;
        }

        $transitions = StatusPendaftaran::transitionsForService($slug);
        $allOptions = [];
        foreach ($transitions as $from => $tos) {
            $allOptions[$from] = StatusPendaftaran::from($from)->getLabel();
            foreach ($tos as $to) {
                $allOptions[$to] = StatusPendaftaran::from($to)->getLabel();
            }
        }

        return $allOptions;
    }

    protected static function isKiblatService(Get $get): bool
    {
        $serviceId = $get('pelayanan_id');
        $service = $serviceId ? Pelayanan::find($serviceId) : null;
        return $service?->slug === 'kalibrasi-arah-kiblat';
    }

    protected static function isNikahService(Get $get): bool
    {
        $serviceId = $get('pelayanan_id');
        $service = $serviceId ? Pelayanan::find($serviceId) : null;
        return $service?->slug === 'pendaftaran-nikah';
    }

    protected static function buildDynamicFields(?string $pelayananId): array
    {
        if (! $pelayananId) {
            return [
                KeyValue::make('data')->label('Data'),
            ];
        }

        $fields = FormField::where('pelayanan_id', $pelayananId)
            ->where('active', true)
            ->orderBy('order')
            ->get();

        if ($fields->isEmpty()) {
            return [
                KeyValue::make('data')->label('Data'),
            ];
        }

        $pelayanan = Pelayanan::find($pelayananId);

        $components = [];
        foreach ($fields as $field) {
            $name = 'data.' . $field->name;
            $required = $field->required;

            $component = match ($field->type) {
                'select' => Select::make($name)->label($field->label)
                    ->options(array_combine(
                        $opts = array_values($field->options ?? []),
                        $opts
                    )),
                'textarea' => Textarea::make($name)->label($field->label),
                'email' => TextInput::make($name)->label($field->label)->email(),
                'tel' => TextInput::make($name)->label($field->label)->tel(),
                'number' => TextInput::make($name)->label($field->label)->numeric(),
                'date' => DateTimePicker::make($name)->label($field->label),
                'file', 'image' => SpatieMediaLibraryFileUpload::make('uploads.' . $field->name)
                    ->label($field->label)
                    ->disk('public')
                    ->collection('pendaftaran_files')
                    ->preserveFilenames()
                    ->when($field->isMultiple(), fn (SpatieMediaLibraryFileUpload $c) => $c->multiple())
                    ->maxSize(($field->getFileUploadConfig()['max_size'] ?? 2048) * 1024)
                    ->when(
                        !empty($field->getFileUploadConfig()['mimes']),
                        fn (SpatieMediaLibraryFileUpload $c) => $c->acceptedFileTypes(static::resolveMimeTypes((array) $field->getFileUploadConfig()['mimes']))
                    )
                    ->when($field->type === 'image', fn (SpatieMediaLibraryFileUpload $c) => $c->image()),
                default => TextInput::make($name)->label($field->label),
            };

            if ($required) {
                $component = $component->required();
            }

            $components[] = $component;
        }

        return $components;
    }

    private static function resolveMimeTypes(array $extensions): array
    {
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'bmp' => 'image/bmp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'csv' => 'text/csv',
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            'mp4' => 'video/mp4',
            'mp3' => 'audio/mpeg',
        ];

        return array_values(array_unique(array_filter(array_map(
            fn ($ext) => $map[strtolower(trim($ext))] ?? null,
            $extensions
        ))));
    }
}
