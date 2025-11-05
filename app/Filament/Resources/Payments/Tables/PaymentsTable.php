<?php

namespace App\Filament\Resources\Payments\Tables;

// ACTIONS
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

// TABLE
use Filament\Tables\Table;

// COLUMNS
use Filament\Tables\Columns\TextColumn;

// FILTERS
use Filament\Forms\Components\DatePicker; // <-- We will use this form component
use Filament\Tables\Filters\Filter; // <-- We will use the base Filter class
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder; // <-- Needed for the custom filter query

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_id')
                    ->label('Member ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tran_id')
                    ->label('Transaction ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount (BDT)')
                    ->sortable()
                    ->numeric(decimalPlaces: 2)
                    ->prefix('BDT '),

                TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(static fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(static fn (string $state): string => ucfirst($state)),

                TextColumn::make('method')
                    ->label('Payment Method')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'paid' => 'Paid',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),
                
                // --- THIS IS THE NEW SOLUTION ---
                // We manually create a filter using a form
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Created From'),
                        DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    // Add this to make the indicator clearer
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['created_from'] && ! $data['created_until']) {
                            return null;
                        }

                        $message = 'Created ';
                        if ($data['created_from']) {
                            $message .= 'from ' . $data['created_from'];
                        }
                        if ($data['created_until']) {
                            $message .= ($data['created_from'] ? ' ' : '') . 'until ' . $data['created_until'];
                        }

                        return $message;
                    }),
                // --- END OF NEW SOLUTION ---
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}