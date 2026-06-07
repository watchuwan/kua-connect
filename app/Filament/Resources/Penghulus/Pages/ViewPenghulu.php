<?php

namespace App\Filament\Resources\Penghulus\Pages;

use App\Filament\Resources\Penghulus\PenghuluResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPenghulu extends ViewRecord
{
    protected static string $resource = PenghuluResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
