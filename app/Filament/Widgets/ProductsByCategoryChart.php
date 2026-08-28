<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductsByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Products by Category';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $categories = Category::withCount('products')->get();

        $colors = [
            'rgba(168, 162, 158, 0.8)',  // stone-400
            'rgba(120, 113, 108, 0.8)',  // stone-500
            'rgba(87, 83, 78, 0.8)',     // stone-600
            'rgba(68, 64, 60, 0.8)',     // stone-700
            'rgba(28, 25, 23, 0.8)',     // stone-900
        ];

        return [
            'datasets' => [
                [
                    'data' => $categories->pluck('products_count')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $categories->count()),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }
}
