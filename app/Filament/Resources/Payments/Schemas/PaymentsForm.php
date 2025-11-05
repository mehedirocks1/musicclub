<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

class PaymentsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('member_id')
                    ->label('Member ID')
                    ->disabled(), // IDs are usually readonly

                TextInput::make('tran_id')
                    ->label('Transaction ID')
                    ->disabled(),

                TextInput::make('amount')
                    ->label('Amount (BDT)')
                    ->numeric()
                    ->prefix('BDT '),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),

                TextInput::make('method')
                    ->label('Payment Method'),

                DateTimePicker::make('created_at')
                    ->label('Created At'),

                DateTimePicker::make('updated_at')
                    ->label('Updated At'),
            ]);
    }
}
