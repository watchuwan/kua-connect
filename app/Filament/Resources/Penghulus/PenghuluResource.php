<?php

namespace App\Filament\Resources\Penghulus;

use App\Filament\Resources\Penghulus\Pages\CreatePenghulu;
use App\Filament\Resources\Penghulus\Pages\EditPenghulu;
use App\Filament\Resources\Penghulus\Pages\ListPenghulus;
use App\Filament\Resources\Penghulus\Pages\ViewPenghulu;
use App\Filament\Resources\Penghulus\Schemas\PenghuluForm;
use App\Filament\Resources\Penghulus\Schemas\PenghuluInfolist;
use App\Filament\Resources\Penghulus\Tables\PenghulusTable;
use App\Models\Penghulu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PenghuluResource extends Resource
{
    protected static ?string $model = Penghulu::class;
    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $slug = 'master/penghulu';

    public static function getNavigationLabel(): string
    {
        return 'Penghulu';
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return PenghuluForm::configure($schema);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return PenghulusTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return PenghuluInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPenghulus::route('/'),
            'create' => CreatePenghulu::route('/create'),
            'view' => ViewPenghulu::route('/{record}'),
            'edit' => EditPenghulu::route('/{record}/edit'),
        ];
    }
}
