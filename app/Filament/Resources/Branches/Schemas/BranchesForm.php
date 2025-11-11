<?php

namespace App\Filament\Resources\Branches\Schemas;

// Change this:
// use Filament\Forms\Components\Card; 
// To this:
use Filament\Schemas\Components\Section; // Check if this is required based on your file's purpose
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BranchesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Change this:
                // Card::make()->schema([
                // To this:
                Section::make('Branch Details') // Optional: Add a title to the section
                    ->schema([
                        TextInput::make('name')
                            ->label('Branch Name')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->maxLength(65535),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('map_url')
                            ->label('Google Maps URL')
                            ->url()
                            ->placeholder('https://maps.google.com/maps?q=Tejgaon+Dhaka&output=embed')
                            ->maxLength(255),
                    ])
            ]);
    }
}