<?php

namespace App\Filament\Resources\Pelayanans\Pages;

use App\Filament\Resources\Pelayanans\PelayananResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPelayanan extends ViewRecord
{
    protected static string $resource = PelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
