<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            Stat::make('Total Products', $totalProducts)
                ->description("{$activeProducts} active, {$featuredProducts} featured")
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('success')
                ->chart([7, 12, 8, 14, 10, 17, 15]),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description("{$totalOrders} total orders")
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('warning')
                ->chart([120, 200, 150, 320, 280, 450, 380]),

            Stat::make('Total Users', $totalUsers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendingOrders > 0 ? 'danger' : 'success'),
        ];
    }
}
