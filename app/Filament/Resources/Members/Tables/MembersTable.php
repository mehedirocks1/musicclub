<?php

namespace App\Filament\Resources\Members\Tables;

use Modules\Members\Models\Member;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_pic')->label('Pic')->circular(),

                Tables\Columns\TextColumn::make('member_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name_bn')
                    ->label('বাংলা নাম')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->toggleable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('membership_type')
                    ->badge(),

                Tables\Columns\TextColumn::make('registration_date')
                    ->date('d-m-Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('balance')
                    ->money('BDT', true),
            ])

            ->filters([
                // SoftDeletes ব্যবহার করলে কাজ করবে
                TrashedFilter::make(),
            ])

            // Row actions (v4): Filament\Actions\*
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])

            // Bulk actions (v4): generic BulkAction ব্যবহার (সবার সাথে কাজ করে)
            ->bulkActions([
                BulkAction::make('delete-selected')
                    ->label('Delete selected')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete())
                    ->deselectRecordsAfterCompletion(),

                // নিচের দুটো SoftDeletes থাকলে আনকমেন্ট করুন
                // BulkAction::make('restore-selected')
                //     ->label('Restore selected')
                //     ->requiresConfirmation()
                //     ->action(fn (Collection $records) => $records->each->restore())
                //     ->visible(fn () => in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive(Member::class))),

                // BulkAction::make('force-delete-selected')
                //     ->label('Force delete selected')
                //     ->color('danger')
                //     ->requiresConfirmation()
                //     ->action(fn (Collection $records) => $records->each->forceDelete())
                //     ->visible(fn () => in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive(Member::class))),
            ]);
    }
}
