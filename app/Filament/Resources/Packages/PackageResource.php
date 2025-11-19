<?php

namespace App\Filament\Resources\Packages;

use BackedEnum;
use App\Filament\Resources\Packages\Pages\CreatePackage;
use App\Filament\Resources\Packages\Pages\EditPackage;
use App\Filament\Resources\Packages\Pages\ListPackages;
use App\Filament\Resources\Packages\Pages\ViewPackage;
use Modules\Packages\Models\Package;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Details')
                    ->description('Main content and descriptive fields for the package')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Package Name')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Set $set) =>
                                    $set('slug', Str::slug($state))
                                ),

                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('auto-generated'),
                        ]),

                        Textarea::make('summary')
                            ->label('Short Summary')
                            ->rows(3)
                            ->maxLength(300)
                            ->extraAttributes(['class' => 'dark:text-gray-300']),

                        /*
                        |--------------------------------------------------------------------------
                        | FIXED RICH EDITOR — removes <p><strong>Full Description</strong></p>
                        |--------------------------------------------------------------------------
                        */
                        RichEditor::make('description')
                            ->label('Full Description')
                            ->disableToolbarButtons(['blockquote'])
                            ->placeholder('Write full description...')
                            ->extraAttributes(['class' => 'dark:text-gray-300'])
                            ->afterStateHydrated(function ($component, $state) {
                                if ($state === '<p><strong>Full Description</strong></p>' ||
                                    trim(strip_tags($state)) === '') {
                                    $component->state(null);
                                }
                            })
                            ->state(function ($state) {
                                if ($state === '<p><strong>Full Description</strong></p>' ||
                                    trim(strip_tags($state)) === '') {
                                    return null;
                                }
                                return $state;
                            }),
                    ]),

                Section::make('Configuration')
                    ->columns(3)
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'paused' => 'Paused',
                                'retired' => 'Retired',
                            ])
                            ->default('draft')
                            ->required(),

                        Select::make('visibility')
                            ->options([
                                'public' => 'Public',
                                'unlisted' => 'Unlisted',
                                'archived' => 'Archived',
                            ])
                            ->default('public')
                            ->required(),
                    ]),

                Section::make('Media')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image_path')
                            ->disk('public')
                            ->directory('packages')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9'),

                        TextInput::make('promo_video_url')->url(),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Tabs::make()
                            ->tabs([
                                Tab::make('Features')->schema([
                                    Repeater::make('features')
                                        ->schema([
                                            TextInput::make('value')->required(),
                                        ])->collapsed(),
                                ]),

                                Tab::make('Prerequisites')->schema([
                                    Repeater::make('prerequisites')
                                        ->schema([
                                            TextInput::make('value')->required(),
                                        ])->collapsed(),
                                ]),
                            ]),
                    ]),

                Section::make('Pricing & Billing')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->minValue(0)
                                ->prefix(fn (Get $get) => $get('currency') ?: 'BDT')
                                ->required(),

                            Select::make('currency')
                                ->options(['BDT' => 'BDT', 'USD' => 'USD'])
                                ->default('BDT'),

                            Select::make('billing_period')
                                ->options([
                                    'one_time' => 'One Time',
                                    'monthly' => 'Monthly',
                                    'yearly' => 'Yearly',
                                ])
                                ->default('one_time')
                                ->reactive(),

                            TextInput::make('access_duration_days')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn (Get $get) => $get('billing_period') === 'one_time'),
                        ]),

                        Grid::make(2)->schema([
                            DateTimePicker::make('sale_starts_at'),
                            DateTimePicker::make('sale_ends_at'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square(),
                Tables\Columns\TextColumn::make('code')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->limit(30)
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'warning',
                        'active' => 'success',
                        'paused' => 'gray',
                        'retired' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('billing_period')->badge(),
                Tables\Columns\TextColumn::make('price')->money(fn ($record) => $record->currency ?: 'BDT'),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'retired' => 'Retired',
                ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->headerActions([CreateAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPackages::route('/'),
            'create' => CreatePackage::route('/create'),
            'view' => ViewPackage::route('/{record}'),
            'edit' => EditPackage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
