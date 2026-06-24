<?php

namespace App\Filament\Resources\Penghulus\Pages;

use App\Filament\Resources\Penghulus\PenghuluResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenghulus extends ListRecords
{
    protected static string $resource = PenghuluResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
