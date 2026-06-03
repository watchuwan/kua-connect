<?php

namespace App\Filament\Resources\Pelayanans;

use App\Filament\Resources\Pelayanans\Pages\CreatePelayanan;
use App\Filament\Resources\Pelayanans\Pages\EditPelayanan;
use App\Filament\Resources\Pelayanans\Pages\ListPelayanans;
use App\Filament\Resources\Pelayanans\Pages\ViewPelayanan;
use App\Filament\Resources\Pelayanans\Schemas\PelayananForm;
use App\Filament\Resources\Pelayanans\Schemas\PelayananInfolist;
use App\Filament\Resources\Pelayanans\Tables\PelayanansTable;
use App\Models\Pelayanan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class PelayananResource extends Resource
{
    protected static ?string $model = Pelayanan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'nama_pelayanan';

    protected static string|UnitEnum|null $navigationGroup = 'Master';

    protected static ?string $modelLabel = 'Pelayanan';

    protected static ?string $pluralModelLabel = 'Pelayanan';

    public static function form(Schema $schema): Schema
    {
        return PelayananForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PelayananInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PelayanansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPelayanans::route('/'),
            'create' => CreatePelayanan::route('/create'),
            'view' => ViewPelayanan::route('/{record}'),
            'edit' => EditPelayanan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user && !$user->hasRole("super_admin") && $user->instansi_id) {
            $query->where("instansi_id", $user->instansi_id);
        }

        return $query;
    }
}
