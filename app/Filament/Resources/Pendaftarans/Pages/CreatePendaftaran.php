<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use App\Models\Pendaftaran;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $today = now()->format('Ymd');
        $last = Pendaftaran::where('nomor_antrean', 'like', "A-{$today}%")
            ->orderByDesc('nomor_antrean')
            ->value('nomor_antrean');

        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        $data['nomor_antrean'] = sprintf('A-%s%04d', $today, $sequence);

        return $data;
    }

    protected function afterCreate(): void
    {
        $nomor = $this->record->nomor_antrean;

        Notification::make()
            ->title('Pendaftaran Berhasil')
            ->body("Nomor antrean: {$nomor}")
            ->success()
            ->send();
    }
}
