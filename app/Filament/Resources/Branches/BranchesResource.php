<?php

namespace App\Filament\Resources\Branches;

use App\Filament\Resources\Branches\Pages\CreateBranches;
use App\Filament\Resources\Branches\Pages\EditBranches;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Filament\Resources\Branches\Pages\ViewBranches;
use App\Filament\Resources\Branches\Schemas\BranchesForm;
use App\Filament\Resources\Branches\Schemas\BranchesInfolist;
use App\Filament\Resources\Branches\Tables\BranchesTable;
use App\Models\Branch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
// CORRECTED IMPORTS
use Filament\Actions\EditAction; 
use Filament\Actions\DeleteAction; 
use Filament\Actions\DeleteBulkAction; 
// The following line is for general purpose Action, not needed for Edit/Delete/Bulk
use Filament\Actions\Action; 

class BranchesResource extends Resource
{
    // Model
    protected static ?string $model = Branch::class;

    // Navigation Icon
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // Record title
    protected static ?string $recordTitleAttribute = 'name';

    // Form
    public static function form(Schema $schema): Schema
    {
        return BranchesForm::configure($schema);
    }

    // Info list
    public static function infolist(Schema $schema): Schema
    {
        return BranchesInfolist::configure($schema);
    }

    // Table
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone'),

                TextColumn::make('map_url')
                    ->label('Map URL')
                    ->url(fn ($record) => $record->map_url)
                    ->openUrlInNewTab()
                    ->limit(50),
            ])
            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    // Relations
    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed
        ];
    }

    // Pages
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