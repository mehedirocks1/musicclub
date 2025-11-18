<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Payments; // Correct payments model
use Illuminate\Support\Carbon;

class MemberPaymentChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Member Payments (Last 12 Months)';

    protected function getType(): string
    {
        return 'bar'; // Bar chart
    }

    protected function getData(): array
    {
        $months = collect();
        $totals = collect();

        // Loop over last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M')); // Jan, Feb, etc.

            // Sum of payments for this month
            $total = Payments::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');

            $totals->push($total);
        }

        return [
            'labels' => $months->toArray(),
            'datasets' => [
                [
                    'label' => 'Payments Received',
                    'data' => $totals->toArray(),
                    'backgroundColor' => '#10B981', // green bars
                    'borderColor' => '#047857',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
