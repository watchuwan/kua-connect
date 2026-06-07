<?php

namespace App\Filament\Resources\Pendaftarans\Pages;

use App\Enums\StatusPendaftaran;
use App\Filament\Resources\Pendaftarans\Actions\TransisiStatusAction;
use App\Filament\Resources\Pendaftarans\PendaftaranResource;
use App\Models\Pendaftaran;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        return [
            ...$this->buildTransitionActions($record),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function buildTransitionActions(Pendaftaran $record): array
    {
        return array_map(
            fn (StatusPendaftaran $target) => TransisiStatusAction::make($target),
            $record->getAllowedTransitions(),
        );
    }
}
