<?php

namespace App\Filament\Resources\Packages;

use App\Filament\Resources\Packages\Pages\CreatePackage;
use App\Filament\Resources\Packages\Pages\EditPackage;
use App\Filament\Resources\Packages\Pages\ListPackages;
use App\Filament\Resources\Packages\Pages\ViewPackage;
use Modules\Packages\Models\Package;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;         // form/infolist এর জন্য
use Filament\Tables\Table;           // table builder

// Forms
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

// Tables
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    // 🔶 MemberResource-এর মতো: form() = Schema + components()
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('code')
                                ->label('Code')
                                ->required()
                                ->unique(ignoreRecord: true),

                            TextInput::make('name')
                                ->label('Name')
                                ->required(),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->prefix(fn ($get) => ($get('currency') ?: 'BDT')),

                            Select::make('currency')
                                ->options(['BDT' => 'BDT', 'USD' => 'USD'])
                                ->default('BDT')
                                ->required(),

                            Select::make('billing_period')
                                ->options([
                                    'one_time' => 'One time',
                                    'monthly'  => 'Monthly',
                                    'yearly'   => 'Yearly',
                                ])
                                ->default('one_time')
                                ->required(),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('access_duration_days')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn ($get) => $get('billing_period') === 'one_time')
                                ->helperText('One-time package মেয়াদ (দিন)'),

                            Select::make('status')
                                ->options([
                                    'draft'   => 'Draft',
                                    'active'  => 'Active',
                                    'paused'  => 'Paused',
                                    'retired' => 'Retired',
                                ])
                                ->default('draft')
                                ->required(),

                            Select::make('visibility')
                                ->options([
                                    'public'   => 'Public',
                                    'unlisted' => 'Unlisted',
                                    'archived' => 'Archived',
                                ])
                                ->default('public')
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            DateTimePicker::make('sale_starts_at')->label('Sale starts'),
                            DateTimePicker::make('sale_ends_at')->label('Sale ends'),
                        ]),

                        FileUpload::make('image_path')
                            ->label('Package Image')
                            ->directory('packages')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->rules(['dimensions:min_width=1200,min_height=675,ratio=16/9'])
                            ->helperText('16:9, min 1200×675'),

                        TextInput::make('promo_video_url')->url()->label('Promo Video URL'),

                        Textarea::make('summary')->rows(2)->maxLength(300),

                        RichEditor::make('description')->columnSpanFull(),

                        Repeater::make('features')
                            ->schema([ TextInput::make('value')->label('Feature') ])
                            ->collapsed(),

                        Repeater::make('prerequisites')
                            ->schema([ TextInput::make('value')->label('Prerequisite') ])
                            ->collapsed(),
                    ])
                    ->columns(1),
            ])
            ->columns(3);
    }

    // 🔶 MemberResource-এর মতো: table() = Table + Tables\Columns প্রিফিক্স
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->square()
                    ->defaultImageUrl(fn () => null),

                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'active',
                        'gray'    => 'paused',
                        'danger'  => 'retired',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_period')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money(fn ($record) => $record->currency ?: 'BDT', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_starts_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sale_ends_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'retired' => 'Retired',
                    ]),
                Tables\Filters\SelectFilter::make('billing_period')
                    ->options([
                        'one_time' => 'One time',
                        'monthly'  => 'Monthly',
                        'yearly'   => 'Yearly',
                    ]),
                Tables\Filters\TrashedFilter::make(), // যদি SoftDeletes আছে
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPackages::route('/'),
            'create' => CreatePackage::route('/create'),
            'view'   => ViewPackage::route('/{record}'),
            'edit'   => EditPackage::route('/{record}/edit'),
        ];
    }

    // (Members-এর মতো soft delete scope ছাড়া route binding করতে চাইলে)
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
