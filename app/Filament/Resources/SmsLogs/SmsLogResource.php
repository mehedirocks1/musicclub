<?php

namespace App\Filament\Resources\SmsLogs;

use App\Filament\Resources\SmsLogs\Pages;
use App\Models\SmsLog;

// 1. Core
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

// 2. LAYOUTS (Unified in v4 Schemas)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Grid;

// 3. FORM COMPONENTS (For form())
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

// 4. INFOLIST COMPONENTS (For infolist() View Page)
// ✅ This is what was missing! TextEntry lives here.
use Filament\Infolists\Components\TextEntry;

// 5. TABLE COMPONENTS
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

// 6. ACTIONS (Global Namespace)
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use UnitEnum;
use BackedEnum;

class SmsLogResource extends Resource
{
    protected static ?string $model = SmsLog::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static UnitEnum|string|null $navigationGroup = 'SMS Management';
    protected static ?string $navigationLabel = 'SMS Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SMS Details')
                    ->schema([
                        TextInput::make('phone')
                            ->required()
                            ->maxLength(255),
                        
                        Select::make('status')
                            ->options([
                                'sent' => 'Sent',
                                'failed' => 'Failed',
                            ])
                            ->required(),

                        Textarea::make('message')
                            ->columnSpanFull(),

                        Textarea::make('error')
                            ->label('Error Log')
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('status') === 'failed'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('message')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Use Grid/Group from Schemas
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])
                ->schema([
                    // LEFT COLUMN
                    Section::make('Message Details')
                        ->icon('heroicon-m-chat-bubble-left-right')
                        ->schema([
                            // ✅ Use TextEntry from Infolists (Supports label, copyable, markdown)
                            TextEntry::make('message')
                                ->label('SMS Content')
                                ->markdown()
                                ->prose()
                                ->columnSpan('full'),

                            TextEntry::make('error')
                                ->label('Failure Reason')
                                ->color('danger')
                                ->fontFamily('mono')
                                ->visible(fn (SmsLog $record) => $record->status === 'failed' && !empty($record->error)),
                        ])
                        ->columnSpan(1),

                    // RIGHT COLUMN
                    Group::make()
                        ->schema([
                            Section::make('Recipient Info')
                                ->schema([
                                    TextEntry::make('phone')
                                        ->label('Phone Number')
                                        ->icon('heroicon-m-phone')
                                        ->copyable(),
                                    
                                    TextEntry::make('status')
                                        ->label('Status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'sent' => 'success',
                                            'failed' => 'danger',
                                            default => 'gray',
                                        }),

                                    TextEntry::make('created_at')
                                        ->label('Sent At')
                                        ->dateTime('d M Y, h:i A')
                                        ->icon('heroicon-m-calendar'),
                                ]),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmsLogs::route('/'),
            'create' => Pages\CreateSmsLog::route('/create'),
            'view' => Pages\ViewSmsLog::route('/{record}'),
            'edit' => Pages\EditSmsLog::route('/{record}/edit'),
        ];
    }
}