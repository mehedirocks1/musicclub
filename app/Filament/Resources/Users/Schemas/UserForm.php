<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('email_verified_at'),

                TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof \App\Filament\Resources\Users\Pages\CreateUser)
                    ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null)
                    ->maxLength(255),

                TextInput::make('member_id')
                    ->numeric()
                    ->label('Member ID')
                    ->nullable(),

                MultiSelect::make('roles')
                    ->label('Roles')
                    ->options(Role::pluck('name', 'name'))
                    ->required(),
            ]);
    }
}
