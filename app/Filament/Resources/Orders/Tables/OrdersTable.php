<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\MemberPayment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TrashedFilter;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(MemberPayment::query()) // Base query for MemberPayment
            ->columns([
                TextColumn::make('tran_id')->label('Transaction ID')->searchable(),
                BadgeColumn::make('status')->colors([
                    'warning' => 'pending',
                    'success' => 'paid',
                    'danger'  => 'failed',
                    'gray'    => 'cancelled',
                ]),
                TextColumn::make('member_id')->label('Member ID')->searchable(),
                TextColumn::make('full_name')->label('Name')->searchable(),
                TextColumn::make('package_name')->label('Package')->searchable(),
                TextColumn::make('plan')->label('Plan')->searchable(),
                TextColumn::make('amount')->money(fn($record) => $record->currency ?: 'BDT', true),
                TextColumn::make('card_type')->label('Card Type')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Phone')->searchable(),
                
                TextColumn::make('gateway')->label('Gateway')->searchable(),
                TextColumn::make('paid_at')->dateTime(),
                TextColumn::make('created_at')->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                TrashedFilter::make(),
            ]);
    }
}
