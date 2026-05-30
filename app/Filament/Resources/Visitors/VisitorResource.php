<?php

namespace App\Filament\Resources\Visitors;

use App\Filament\Resources\Visitors\Pages\ListVisitors;
use App\Filament\Resources\Visitors\Pages\ViewVisitor;
use App\Filament\Resources\Visitors\Schemas\VisitorInfolist;
use App\Filament\Resources\Visitors\Tables\VisitorsTable;
use App\Models\Visitor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $recordTitleAttribute = 'ip_address';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'Pengunjung';

    protected static ?string $pluralModelLabel = 'Pengunjung';

    public static function infolist(Schema $schema): Schema
    {
        return VisitorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisitorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisitors::route('/'),
            'view' => ViewVisitor::route('/{record}'),
        ];
    }
}
