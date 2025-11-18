<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\MemberPayment;
use Illuminate\Support\Carbon;

class PackageSalesChart extends ChartWidget
{
    protected ?string $heading = 'Package Sales & Earnings (Last 6 Months)';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $payments = MemberPayment::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('package_id') // Payment related to a package
            ->get();

        $grouped = $payments->groupBy('package_name');

        $labels = [];
        $packageCount = [];
        $packageEarnings = [];

        foreach ($grouped as $packageName => $items) {
            $labels[] = $packageName ?? 'Unknown Package';
            $packageCount[] = $items->count();
            $packageEarnings[] = $items->sum('amount');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Packages Sold',
                    'data' => $packageCount,
                    'backgroundColor' => [
                        '#F59E0B', '#10B981', '#3B82F6', '#F87171', '#A78BFA'
                    ],
                ],
                [
                    'label' => 'Earnings (BDT)',
                    'data' => $packageEarnings,
                    'backgroundColor' => [
                        '#FBBF24', '#34D399', '#60A5FA', '#FCA5A5', '#C4B5FD'
                    ],
                ],
            ],

            // ⭐ TOOLTIP OVERRIDE ⭐
            'options' => [
                'plugins' => [
                    'tooltip' => [
                        'callbacks' => [
                            'label' => function ($tooltipItem) {
                                $value = $tooltipItem['formattedValue'];
                                $datasetIndex = $tooltipItem['datasetIndex'];

                                if ($datasetIndex === 0) {
                                    return "Sold: {$value}";
                                }
                                if ($datasetIndex === 1) {
                                    return "Earnings: ৳{$value}";
                                }

                                return $value;
                            },
                        ],
                    ],
                ],
            ],
        ];
    }
}
