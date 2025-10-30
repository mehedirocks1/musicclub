<?php

namespace App\Filament\Resources\Abouts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;

class AboutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('founded_year')
                            ->numeric()
                            ->label('Founded Year'),
                        TextInput::make('members_count')
                            ->numeric()
                            ->label('Members'),
                        FileUpload::make('hero_image')
                            ->label('Hero Image')
                            ->directory('about')
                            ->image(),
                    ])
                    ->columns(2),

                Section::make('Text Content')
                    ->schema([
                        Textarea::make('short_description')
                            ->label('Short Description'),
                        Textarea::make('mission')
                            ->label('Mission'),
                        Textarea::make('vision')
                            ->label('Vision'),
                        TagsInput::make('activities')
                            ->label('Activities')
                            ->placeholder('Add an activity...'),
                    ])
                    ->columns(1),
            ]);
    }
}
