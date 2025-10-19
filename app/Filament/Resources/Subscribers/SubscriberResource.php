<?php

namespace App\Filament\Resources\Subscribers;

use App\Filament\Resources\Subscribers\Pages\CreateSubscriber;
use App\Filament\Resources\Subscribers\Pages\EditSubscriber;
use App\Filament\Resources\Subscribers\Pages\ListSubscribers;
use App\Filament\Resources\Subscribers\Pages\ViewSubscriber;
use App\Filament\Resources\Subscribers\Schemas\SubscriberInfolist;
use App\Models\Subscriber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// === REQUIRED IMPORTS FOR FORM COMPONENTS ===
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
// If the table uses these, ensure they are imported
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables;


class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'full_name';

    // ✅ FIX: Signature updated to use Schema, compatible with Filament v3/v4
    public static function form(Schema $schema): Schema
    {
        return $schema->components([ // Use ->components() for the main Schema container
            Section::make('Profile')->schema([
                TextInput::make('subscriber_id')->unique(ignoreRecord: true)->required(),
                TextInput::make('username')->unique(ignoreRecord: true)->required(),
                TextInput::make('full_name')->required(),
                TextInput::make('email')->email()->unique(ignoreRecord: true),
                TextInput::make('phone')->tel()->unique(ignoreRecord: true),
                TextInput::make('password')->password()->dehydrateStateUsing(fn($s)=>$s? bcrypt($s):null)->nullable(),
            ])->columns(2),
            Section::make('Details')->schema([
                DatePicker::make('dob'),
                Select::make('gender')->options(['Male'=>'Male','Female'=>'Female','Other'=>'Other']),
                TextInput::make('profession'),
                TextInput::make('country')->default('Bangladesh'),
                TextInput::make('division'),
                TextInput::make('district'),
                TextInput::make('address'),
            ])->columns(2),
            Section::make('Lifecycle')->schema([
                Select::make('plan')->options(['monthly'=>'Monthly','yearly'=>'Yearly'])->nullable(),
                Select::make('status')->options([
                    'pending'=>'Pending','active'=>'Active','expired'=>'Expired','inactive'=>'Inactive'
                ])->default('pending'),
                DatePicker::make('started_at'),
                DatePicker::make('expires_at'),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SubscriberInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('subscriber_id')->badge(),
            TextColumn::make('full_name')->searchable(),
            TextColumn::make('email')->searchable(),
            BadgeColumn::make('status')->colors([
                'warning'=>'pending','success'=>'active','danger'=>'expired','gray'=>'inactive'
            ]),
            TextColumn::make('plan')->badge(),
            TextColumn::make('expires_at')->date(),
            TextColumn::make('updated_at')->since(),
        ])->defaultSort('updated_at','desc')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
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
            'index' => ListSubscribers::route('/'),
            'create' => CreateSubscriber::route('/create'),
            'view' => ViewSubscriber::route('/{record}'),
            'edit' => EditSubscriber::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}