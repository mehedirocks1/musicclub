<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Gallery Details') // Optional title
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),

                        Select::make('category')
                            ->label('Category')
                            ->options([
                                'live' => 'Live',
                                'workshop' => 'Workshop',
                                'members' => 'Members',
                            ])
                            ->required(),
FileUpload::make('image')
    ->label('Image')
    ->image()
    ->disk('public')           // correct: public disk
    ->directory('gallaries')   // correct folder
    ->visibility('public')     // correct visibility
    ->required(),


                    ])
            ]);
    }
}
