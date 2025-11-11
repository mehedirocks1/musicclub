<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Filament\Resources\Galleries\Schemas\GalleryForm;
use App\Filament\Resources\Galleries\Tables\GalleriesTable;
use App\Models\Gallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Gallery';

    public static function form(Schema $schema): Schema
    {
        return GalleryForm::configure($schema);
    }



public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('title')
                ->label('Title')
                ->sortable()
                ->searchable(),

            TextColumn::make('category')
                ->label('Category')
                ->sortable(),

            ImageColumn::make('image')
                ->label('Image')
                ->disk('public') // points to storage/app/public
                ->getStateUsing(fn ($record) => $record->image ? $record->image : null)
                ->url(fn ($record) => $record->image ? asset('storage/' . $record->image) : null)
                ->square()
                ->rounded(),
        ])
        ->filters([
            //
        ]);
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
            'index' => ListGalleries::route('/'),
            'create' => CreateGallery::route('/create'),
            'edit' => EditGallery::route('/{record}/edit'),
        ];
    }
}
