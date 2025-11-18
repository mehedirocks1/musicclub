<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Packages\Models\Package;
use Modules\Subscribers\Models\Subscriber;
use Modules\Members\Models\Member;
use App\Models\Payments;
use App\Models\MemberPayment;

class ModuleStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Count how many packages were purchased (only paid & has package_id)
        $packagesSoldCount = MemberPayment::whereNotNull('package_id')
            ->where('status', 'paid')
            ->count();

        // Total revenue from package sales
        $packageRevenue = MemberPayment::whereNotNull('package_id')
            ->where('status', 'paid')
            ->sum('amount');

        return [

            // Total Packages
            Stat::make('Total Packages', Package::count())
                ->description('All packages')
                ->color('success'),

            // Total Subscribers
            Stat::make('Subscribers', Subscriber::count())
                ->description('All subscribers')
                ->color('warning'),

            // Total Members
            Stat::make('Members', Member::count())
                ->description('All members')
                ->color('secondary'),

            // Total Payments Received
            Stat::make('Total Payments', Payments::sum('amount'))
                ->description('Total amount received from members')
                ->color('primary'),

            // Packages purchased (count)
            Stat::make('Packages purchased', $packagesSoldCount)
                ->description('Number of packages sold (paid)')
                ->color('success'),

            // Package Revenue (sum)
            Stat::make('Package Revenue', number_format((float)$packageRevenue, 2) . ' BDT')
                ->description('Total amount earned from package sales')
                ->color('primary'),
        ];
    }
}
