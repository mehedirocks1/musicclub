<?php

namespace App\Filament\Resources\Abouts\Schemas;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class AboutInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('founded_year')
                    ->label('Founded')
                    ->sortable(),

                TextColumn::make('members_count')
                    ->label('Members')
                    ->sortable(),

                TextColumn::make('events_per_year')
                    ->label('Events/Yr')
                    ->sortable(),
            ]);
    }
}
