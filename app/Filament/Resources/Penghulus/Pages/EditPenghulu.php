<?php

namespace App\Filament\Resources\Penghulus\Pages;

use App\Filament\Resources\Penghulus\PenghuluResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenghulu extends EditRecord
{
    protected static string $resource = PenghuluResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
