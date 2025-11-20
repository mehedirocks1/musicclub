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

use Livewire\Component as Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms;

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
        return $schema->components([
            Section::make('Package Details')
                ->description('Main content and descriptive fields for the package')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->label('Package Name')
                                ->placeholder('e.g. Professional Music Production')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->placeholder('auto-generated from name')
                                ->required()
                                ->unique(ignoreRecord: true),
                        ]),

                    Textarea::make('summary')
                        ->label('Short Summary')
                        ->placeholder('One or two lines summary for listings')
                        ->rows(3)
                        ->maxLength(300),

                    RichEditor::make('description')
                        ->label('Full Description')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'bulletList',
                            'orderedList',
                            'link',
                            'codeBlock',
                        ])
                        ->placeholder('Describe what the package contains, outcomes and who it is for'),
                ]),

            Section::make('Configuration')
                ->description('Package settings')
                ->columns(3)
                ->schema([
                    TextInput::make('code')
                        ->label('Package Code')
                        ->placeholder('e.g. PM-001')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Unique identifier for billing'),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'active' => 'Active',
                            'paused' => 'Paused',
                            'retired' => 'Retired',
                        ])
                        ->default('draft')
                        ->required()
                        ->searchable(),

                    Select::make('visibility')
                        ->label('Visibility')
                        ->options([
                            'public' => 'Public',
                            'unlisted' => 'Unlisted',
                            'archived' => 'Archived',
                        ])
                        ->default('public')
                        ->required()
                        ->helperText('Controls public listing visibility'),
                ]),

            Section::make('Media')
                ->description('Images and promotional content')
                ->columns(2)
                ->schema([
                    FileUpload::make('image_path')
                        ->label('Package Image')
                        ->disk('public')
                        ->directory('packages')
                        ->visibility('public')
                        ->image()
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->rules(['dimensions:min_width=1200,min_height=675,ratio=16/9'])
                        ->helperText('16:9 aspect ratio — minimum 1200×675px'),

                    TextInput::make('promo_video_url')
                        ->label('Promo Video URL')
                        ->url()
                        ->placeholder('https://youtu.be/your-promo')
                        ->helperText('Optional embeddable video link'),
                ]),

            Section::make('Additional Information')
                ->schema([
                    Tabs::make('Details')
                        ->tabs([
                            Tab::make('Features')
                                ->schema([
                                    Repeater::make('features')
                                        ->label('Package Features')
                                        ->schema([
                                            TextInput::make('value')
                                                ->label('Feature')
                                                ->placeholder('e.g. Certificate of Completion')
                                                ->required(),
                                        ])
                                        ->collapsed()
                                        ->defaultItems(1)
                                        ->addActionLabel('Add Feature'),
                                ]),

                            Tab::make('Prerequisites')
                                ->schema([
                                    Repeater::make('prerequisites')
                                        ->label('Prerequisites')
                                        ->schema([
                                            TextInput::make('value')
                                                ->label('Prerequisite')
                                                ->placeholder('e.g. Basic music theory')
                                                ->required(),
                                        ])
                                        ->collapsed()
                                        ->defaultItems(1)
                                        ->addActionLabel('Add Prerequisite'),
                                ]),
                        ]),
                ]),

            Section::make('Pricing & Billing')
                ->description('Set pricing and billing configuration')
                ->schema([
                    Grid::make(4)
                        ->schema([
                            TextInput::make('price')
                                ->label('Price')
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->prefix(fn (Get $get) => $get('currency') ?: 'BDT')
                                ->placeholder('0.00'),

                            Select::make('currency')
                                ->label('Currency')
                                ->options([
                                    'BDT' => 'BDT',
                                    'USD' => 'USD',
                                ])
                                ->default('BDT')
                                ->required(),

                            Select::make('billing_period')
                                ->label('Billing Period')
                                ->options([
                                    'one_time' => 'One Time',
                                    'monthly' => 'Monthly',
                                    'yearly' => 'Yearly',
                                ])
                                ->default('one_time')
                                ->required()
                                ->reactive(),

                            TextInput::make('access_duration_days')
                                ->label('Access Duration (days)')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn (Get $get) => $get('billing_period') === 'one_time')
                                ->helperText('Duration for one-time packages')
                                ->placeholder('30'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            DateTimePicker::make('sale_starts_at')->label('Sale Starts At'),
                            DateTimePicker::make('sale_ends_at')->label('Sale Ends At'),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->square()
                    ->defaultImageUrl('/images/placeholder-16x9.png'),

                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->limit(30)
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'warning',
                        'active' => 'success',
                        'paused' => 'gray',
                        'retired' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_period')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money(fn ($record) => $record->currency ?: 'BDT', false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_starts_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sale_ends_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->since(),
            ])

            ->defaultSort('updated_at', 'desc')

            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'retired' => 'Retired',
                ]),

                Tables\Filters\SelectFilter::make('billing_period')->options([
                    'one_time' => 'One time',
                    'monthly' => 'Monthly',
                    'yearly' => 'Yearly',
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

            ->headerActions([
                CreateAction::make(),
            ])

            ->toolbarActions([
                BulkAction::make('delete-selected')
                    ->label('Delete selected')
                    ->requiresConfirmation()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $records->each->delete();
                    }),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
