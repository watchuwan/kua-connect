<?php

namespace App\Filament\Resources\Pelayanans\Pages;

use App\Filament\Resources\Pelayanans\PelayananResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPelayanans extends ListRecords
{
    protected static string $resource = PelayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
