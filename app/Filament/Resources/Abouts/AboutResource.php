<?php

namespace App\Filament\Resources\Abouts;

use App\Filament\Resources\Abouts\Pages\CreateAbout;
use App\Filament\Resources\Abouts\Pages\EditAbout;
use App\Filament\Resources\Abouts\Pages\ListAbouts;
use App\Models\About;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;

use BackedEnum;
use UnitEnum;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Content Management';
    protected static ?string $navigationLabel = 'About Page Settings';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $defaultSort = 'id';
    protected static ?string $defaultSortDirection = 'asc';

  public static function form(Schema $schema): Schema
{
    return $schema
        ->schema([
            Grid::make(3)
                ->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->default('POJ Music Club')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('founded_year')
                        ->numeric()
                        ->label('Founded Year')
                        ->minValue(1900)
                        ->maxValue(date('Y') + 1),
                    TextInput::make('members_count')
                        ->numeric()
                        ->label('Members')
                        ->default(2000),
                    TextInput::make('events_per_year')
                        ->numeric()
                        ->label('Events / Year')
                        ->default(120),
                    FileUpload::make('hero_image')
                        ->label('Hero Image')
                        ->directory('about')
                        ->disk('public')          // ✅ Uploads to public disk
                        ->image()
                        ->imageEditor()
                        ->columnSpanFull(),
                ]),

            Grid::make(1)
                ->schema([
                    Textarea::make('short_description')->label('Short Description')->rows(3),
                    Textarea::make('mission')->label('Mission')->rows(3),
                    Textarea::make('vision')->label('Vision')->rows(3),
                ]),

            Grid::make(1)
                ->schema([
                    TagsInput::make('activities')
                        ->label('What We Do')
                        ->placeholder('Add activity...')
                        ->suggestions([
                            'Live shows & jam nights',
                            'Workshops & masterclasses',
                            'Student showcases',
                            'Community projects & collaborations',
                        ]),
                ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('founded_year')->label('Founded')->sortable(),
                TextColumn::make('members_count')->label('Members')->sortable(),
                TextColumn::make('events_per_year')->label('Events/Yr')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
