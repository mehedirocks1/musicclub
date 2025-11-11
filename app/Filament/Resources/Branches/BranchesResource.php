<?php

namespace App\Filament\Resources\Branches;

use App\Filament\Resources\Branches\Pages\CreateBranches;
use App\Filament\Resources\Branches\Pages\EditBranches;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Filament\Resources\Branches\Pages\ViewBranches;
use App\Filament\Resources\Branches\Schemas\BranchesForm;
use App\Filament\Resources\Branches\Schemas\BranchesInfolist;
use App\Filament\Resources\Branches\Tables\BranchesTable;
use App\Models\Branch; // ✅ fixed model import
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
class BranchesResource extends Resource
{
    // ✅ fixed model class
    protected static ?string $model = Branch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BranchesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BranchesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranches::route('/'),
            'create' => CreateBranches::route('/create'),
            'view' => ViewBranches::route('/{record}'),
            'edit' => EditBranches::route('/{record}/edit'),
        ];
    }
}
