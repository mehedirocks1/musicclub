<?php

namespace App\Filament\Resources\Members;

use BackedEnum;
use App\Filament\Resources\Members\Pages\CreateMember;
use App\Filament\Resources\Members\Pages\EditMember;
use App\Filament\Resources\Members\Pages\ListMembers;
use App\Filament\Resources\Members\Pages\ViewMember;
use Modules\Members\Models\Member;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('profile_pic')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('members/profile_pics'),

                TextInput::make('member_id')
                    ->label('Member ID')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(50),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_bn')
                    ->label('Name (Bangla)')
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('father_name')
                    ->maxLength(255),

                TextInput::make('mother_name')
                    ->maxLength(255),

                DatePicker::make('dob')
                    ->label('Date of Birth'),

                TextInput::make('id_number')
                    ->label('NID / Passport / Student ID')
                    ->maxLength(100),

                Select::make('gender')
                    ->options(array_combine(Member::GENDERS, Member::GENDERS))
                    ->searchable(),

                Select::make('blood_group')
                    ->options(array_combine(Member::BLOOD_GROUPS, Member::BLOOD_GROUPS))
                    ->searchable(),

                TextInput::make('education_qualification')
                    ->label('Education Qualification'),

                TextInput::make('profession'),

                TextInput::make('other_expertise')
                    ->label('Other Expertise'),

                TextInput::make('country')->default('Bangladesh'),
                TextInput::make('division'),
                TextInput::make('district'),
                Textarea::make('address'),

                Select::make('membership_type')
                    ->options(array_combine(Member::MEMBERSHIP_TYPES, Member::MEMBERSHIP_TYPES))
                    ->required(),

                DatePicker::make('registration_date')
                    ->label('Registration Date')
                    ->default(now()),

                TextInput::make('balance')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('৳'),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_pic')->circular()->label('Photo'),
                TextColumn::make('member_id')->sortable()->searchable(),
                TextColumn::make('full_name')->sortable()->searchable(),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('membership_type')->badge()->color(fn ($state) => match ($state) {
                    'Student' => 'info',
                    'General' => 'gray',
                    'Premium' => 'success',
                    'Lifetime' => 'warning',
                    default => 'gray',
                }),
                TextColumn::make('balance')->money('BDT'),
                TextColumn::make('registration_date')->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('membership_type')
                    ->options(array_combine(Member::MEMBERSHIP_TYPES, Member::MEMBERSHIP_TYPES)),
            ])
           ;
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'edit'   => EditMember::route('/{record}/edit'),
        ];
    }
}
