<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?int $navigationSort = 0;
    protected static ?string $title = 'Analytics Dashboard';
    protected static ?string $slug = 'analytics';
    protected static string $view = 'filament.pages.analytics';

    public ?string $dateRange = '30d';

    public static function getNavigationItems(): array
    {
        return [
            [
                'icon' => 'heroicon-o-chart-bar',
                'label' => 'Analytics',
                'url' => static::getUrl(),
                'isActive' => fn (Page $page): bool => static::class === $page::class || $page::class === \Filament\Pages\Dashboard::class,
                'sort' => static::$navigationSort ?? 0,
                'badge' => null,
                'badgeColor' => null,
                'group' => static::$navigationGroup,
                'grouped' => static::$navigationGroup !== null,
                'isSidebarCollapsibleOnDesktop' => false,
                'isActiveWhen' => fn () => request()->routeIs('filament.admin.pages.analytics'),
            ],
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $this->dateRange = request()->get('dateRange', '30d');
    }

    public function setDateRange(string $range): void
    {
        $this->dateRange = $range;
    }

    public function getStats(): array
    {
        $days = match ($this->dateRange) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            '1y' => 365,
            default => 30,
        };

        $since = Carbon::now()->subDays($days);

        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $since)
            ->sum('total');

        $totalOrders = Order::where('created_at', '>=', $since)->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $newCustomers = User::where('created_at', '>=', $since)->count();

        $prevSince = Carbon::now()->subDays($days * 2)->startOfDay();
        $prevUntil = $since;

        $prevRevenue = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$prevSince, $prevUntil])
            ->sum('total');

        $prevOrders = Order::whereBetween('created_at', [$prevSince, $prevUntil])->count();

        return [
            'total_revenue' => '$' . number_format($totalRevenue, 2),
            'revenue_change' => $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : null,
            'total_orders' => $totalOrders,
            'orders_change' => $prevOrders > 0 ? round((($totalOrders - $prevOrders) / $prevOrders) * 100, 1) : null,
            'avg_order_value' => '$' . number_format($avgOrderValue, 2),
            'new_customers' => $newCustomers,
            'products_sold' => OrderItem::whereHas('order', fn ($q) => $q->where('created_at', '>=', $since)->where('status', '!=', 'cancelled'))->sum('quantity'),
            'conversion_rate' => $newCustomers > 0 ? round(($totalOrders / max($newCustomers, 1)) * 100, 1) . '%' : '—',
        ];
    }

    public function getSalesOverTimeData(): array
    {
        $days = match ($this->dateRange) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            '1y' => 365,
            default => 30,
        };

        $since = Carbon::now()->subDays($days);

        $sales = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $since)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $revenueData = [];
        $ordersData = [];

        // Fill in missing dates
        $current = $since->copy()->startOfDay();
        $end = Carbon::now()->endOfDay();

        while ($current->lte($end)) {
            $dateStr = $current->toDateString();
            $labels[] = $current->format($days <= 7 ? 'D' : ($days <= 30 ? 'M d' : 'M Y'));

            $sale = $sales->firstWhere('date', $dateStr);
            $revenueData[] = $sale ? round($sale->revenue, 2) : 0;
            $ordersData[] = $sale ? $sale->orders : 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }

    public function getRevenueByCategoryData(): array
    {
        $data = OrderItem::whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_category', 'products.id', '=', 'product_category.product_id')
            ->join('categories', 'product_category.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        $colors = [
            'rgba(168, 162, 158, 0.85)',
            'rgba(120, 113, 108, 0.85)',
            'rgba(87, 83, 78, 0.85)',
            'rgba(68, 64, 60, 0.85)',
            'rgba(28, 25, 23, 0.85)',
        ];

        return [
            'labels' => $data->pluck('name')->toArray(),
            'data' => $data->pluck('revenue')->map(fn ($v) => round($v, 2))->toArray(),
            'colors' => array_slice($colors, 0, $data->count()),
        ];
    }

    public function getTopProductsData(): array
    {
        return OrderItem::whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.sku',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getOrdersByStatusData(): array
    {
        $data = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $colors = [
            'pending' => 'rgba(251, 191, 36, 0.85)',
            'processing' => 'rgba(96, 165, 250, 0.85)',
            'shipped' => 'rgba(52, 211, 153, 0.85)',
            'delivered' => 'rgba(34, 197, 94, 0.85)',
            'cancelled' => 'rgba(248, 113, 113, 0.85)',
        ];

        return [
            'labels' => $data->pluck('status')->map(fn ($s) => ucfirst($s))->toArray(),
            'data' => $data->pluck('count')->toArray(),
            'colors' => $data->pluck('status')->map(fn ($s) => $colors[$s] ?? 'rgba(156,163,175,0.85)')->toArray(),
        ];
    }

    public function getCustomerGeographyData(): array
    {
        $data = Order::select('shipping_country', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('shipping_country')
            ->orderByDesc('revenue')
            ->get();

        return [
            'labels' => $data->pluck('shipping_country')->toArray(),
            'orders' => $data->pluck('count')->toArray(),
            'revenue' => $data->pluck('revenue')->map(fn ($v) => round($v, 2))->toArray(),
        ];
    }

    public function getRecentOrders(): array
    {
        return Order::with('user', 'items.product')
            ->latest()
            ->limit(10)
            ->get()
            ->toArray();
    }
}
