<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\Members\Models\Member;
use Illuminate\Support\Carbon;

class MemberRegistrationChart extends ChartWidget
{
    protected ?string $heading = 'Member Registration (Last 12 Months)';

    protected function getType(): string
    {
        return 'line'; // You can also use 'bar' if you prefer
    }

    protected function getData(): array
    {
        $months = collect();
        $counts = collect();

        // Loop over last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M')); // e.g., Jan, Feb
            $counts->push(
                Member::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count()
            );
        }

        return [
            'labels' => $months->toArray(),
            'datasets' => [
                [
                    'label' => 'Members',
                    'data' => $counts->toArray(),
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#1D4ED8',
                    'fill' => true,
                ],
            ],
        ];
    }
}
