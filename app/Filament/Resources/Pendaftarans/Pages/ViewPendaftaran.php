<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Enums\StatusPendaftaran;
use App\Filament\Resources\Pendaftarans\Actions\TransisiStatusAction;
use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use App\Models\Pendaftaran;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPendaftaran extends ViewRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        return [
            ...$this->buildTransitionActions($record),
            EditAction::make(),
        ];
    }

    protected function buildTransitionActions(Pendaftaran $record): array
    {
        if ($record->trashed()) {
            return [];
        }

        return array_map(
            fn (StatusPendaftaran $target) => TransisiStatusAction::make($target),
            $record->getAllowedTransitions(),
        );
    }
}
