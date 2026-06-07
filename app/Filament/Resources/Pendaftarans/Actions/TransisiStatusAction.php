<?php

namespace App\Filament\Resources\Pendaftarans\Actions;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class TransisiStatusAction
{
    public static function make(StatusPendaftaran $target): Action
    {
        $action = Action::make('to_' . $target->value)
            ->label($target->getLabel())
            ->icon($target->getIcon())
            ->color($target->getColor())
            ->visible(fn (Pendaftaran $record): bool => $record->canTransitionTo($target))
            ->action(function (array $data, Pendaftaran $record) use ($target) {
                static::transisiStatus($record, $target, $data);
            });

        $action = $action->requiresConfirmation()->modalHeading($target->getLabel());

        if ($target === StatusPendaftaran::PerluRevisi) {
            $action = $action->schema([
                Textarea::make('catatan')
                    ->label('Apa yang perlu direvisi?')
                    ->placeholder('Jelaskan bagian apa saja yang perlu diperbaiki...')
                    ->required()
                    ->rows(3),
            ]);
        } else {
            $action = $action
                ->schema(fn (Pendaftaran $record): array => static::getFormSchema($record, $target));
        }

        return $action;
    }

    public static function getFormSchema(Pendaftaran $record, StatusPendaftaran $target): array
    {
        if ($target === StatusPendaftaran::VerifikasiFisik && $record->pelayanan?->slug === 'pendaftaran-nikah') {
            return [
                DateTimePicker::make('jadwal_kedatangan')
                    ->label('Jadwal Verifikasi Fisik')
                    ->required()
                    ->native(false),
                DateTimePicker::make('jadwal_bimwin')
                    ->label('Jadwal Bimwin')
                    ->required()
                    ->native(false),
            ];
        }

        if ($target === StatusPendaftaran::JadwalDikunci && $record->pelayanan?->slug === 'pendaftaran-nikah') {
            return [
                Select::make('penghulu_id')
                    ->label('Plot Penghulu')
                    ->relationship('penghulu', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
            ];
        }

        if ($target === StatusPendaftaran::SiapIkrar && $record->pelayanan?->slug === 'sertifikasi-wakaf') {
            return [
                DateTimePicker::make('jadwal_kedatangan')
                    ->label('Jadwal Ikrar')
                    ->required()
                    ->native(false)
                    ->minutesStep(5),
            ];
        }

        if ($target === StatusPendaftaran::JadwalDitugaskan && $record->pelayanan?->slug === 'kalibrasi-arah-kiblat') {
            return [
                DateTimePicker::make('jadwal_kedatangan')
                    ->label('Jadwal Kunjungan Kalibrasi')
                    ->required()
                    ->native(false)
                    ->minutesStep(5),
            ];
        }

        if ($target === StatusPendaftaran::Selesai && $record->pelayanan?->slug === 'kalibrasi-arah-kiblat') {
            return [
                TextInput::make('derajat_kiblat')
                    ->label('Derajat Arah Kiblat')
                    ->numeric()
                    ->required()
                    ->suffix('°'),
                SpatieMediaLibraryFileUpload::make('file_sertifikat_akurasi')
                    ->label('Sertifikat Akurasi Arah Kiblat')
                    ->disk('public')
                    ->collection('pendaftaran_files')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048),
            ];
        }

        return [];
    }

    public static function transisiStatus(Pendaftaran $record, StatusPendaftaran $target, array $data = []): void
    {
        $extra = [];

        if (isset($data['catatan'])) {
            $extra['catatan'] = $data['catatan'];
        }
        if (isset($data['jadwal_kedatangan'])) {
            $extra['jadwal_kedatangan'] = $data['jadwal_kedatangan'];
        }
        if (isset($data['jadwal_bimwin'])) {
            $extra['jadwal_bimwin'] = $data['jadwal_bimwin'];
        }
        if (isset($data['penghulu_id'])) {
            $extra['penghulu_id'] = $data['penghulu_id'];
        }
        if (isset($data['derajat_kiblat'])) {
            $extra['derajat_kiblat'] = $data['derajat_kiblat'];
        }

        $updates = array_merge(['status' => $target], $extra);

        if ($target === StatusPendaftaran::VerifikasiFisik) {
            $updates['waktu_dilayani'] = now();
        }

        if ($target === StatusPendaftaran::Selesai) {
            $updates['waktu_selesai'] = now();
        }

        $record->update($updates);

        Notification::make()
            ->title('Status berhasil diubah')
            ->body("Status {$record->nomor_antrean} → {$target->getLabel()}")
            ->success()
            ->send();
    }

    public static function getAllTransitionActions(): array
    {
        return array_map(
            fn (StatusPendaftaran $target) => static::make($target),
            StatusPendaftaran::cases(),
        );
    }
}
