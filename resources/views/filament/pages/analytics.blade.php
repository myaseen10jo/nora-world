<x-filament-panels::page
    @filament-panels::page-heading-tag
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white dark:bg-white/5 rounded-lg p-1">
                @foreach(['7d' => '7 Days', '30d' => '30 Days', '90d' => '90 Days', '1y' => '1 Year'] as $value => $label)
                <button
                    wire:click="mountAction('setDateRange', '{{ $value }}')"
                    class="px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ $dateRange === $value ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                >
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
    @end
>
    @php
        $stats = $this->getStats();
        $salesData = $this->getSalesOverTimeData();
        $categoryData = $this->getRevenueByCategoryData();
        $topProducts = $this->getTopProductsData();
        $statusData = $this->getOrdersByStatusData();
        $geoData = $this->getCustomerGeographyData();
        $recentOrders = $this->getRecentOrders();
    @endphp

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STAT CARDS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Revenue --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl p-5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Revenue</span>
                <div class="w-9 h-9 bg-success-50 dark:bg-success-500/10 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-success-600 dark:text-success-400" />
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_revenue'] }}</div>
            @if($stats['revenue_change'] !== null)
            <div class="flex items-center gap-1 mt-1">
                <span class="text-xs font-medium {{ $stats['revenue_change'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                    {{ $stats['revenue_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['revenue_change']) }}%
                </span>
                <span class="text-xs text-gray-400">vs previous period</span>
            </div>
            @endif
        </div>

        {{-- Orders --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl p-5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Orders</span>
                <div class="w-9 h-9 bg-primary-50 dark:bg-primary-500/10 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-shopping-bag class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_orders']) }}</div>
            @if($stats['orders_change'] !== null)
            <div class="flex items-center gap-1 mt-1">
                <span class="text-xs font-medium {{ $stats['orders_change'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                    {{ $stats['orders_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['orders_change']) }}%
                </span>
                <span class="text-xs text-gray-400">vs previous period</span>
            </div>
            @endif
        </div>

        {{-- Avg Order Value --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl p-5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">Avg Order Value</span>
                <div class="w-9 h-9 bg-warning-50 dark:bg-warning-500/10 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-receipt-percent class="w-5 h-5 text-warning-600 dark:text-warning-400" />
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['avg_order_value'] }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $stats['products_sold'] }} products sold</div>
        </div>

        {{-- New Customers --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl p-5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-500">New Customers</span>
                <div class="w-9 h-9 bg-info-50 dark:bg-info-500/10 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-users class="w-5 h-5 text-info-600 dark:text-info-400" />
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['new_customers'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Conversion: {{ $stats['conversion_rate'] }}</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SALES OVER TIME CHART --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 mb-8">
        <div class="p-5 border-b border-gray-100 dark:border-white/10">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Over Time</h3>
            <p class="text-sm text-gray-500 mt-0.5">Revenue and order volume for the selected period</p>
        </div>
        <div class="p-5">
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- REVENUE BY CATEGORY + ORDER STATUS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Revenue by Category --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
            <div class="p-5 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue by Category</h3>
                <p class="text-sm text-gray-500 mt-0.5">Which categories drive the most revenue</p>
            </div>
            <div class="p-5 flex items-center justify-center">
                @if(count($categoryData['data']) > 0)
                <canvas id="categoryChart" width="300" height="300"></canvas>
                @else
                <div class="text-center py-12 text-gray-400">
                    <x-heroicon-o-chart-pie class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p class="text-sm">No sales data yet</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Order Status Breakdown --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
            <div class="p-5 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Status</h3>
                <p class="text-sm text-gray-500 mt-0.5">Breakdown of orders by current status</p>
            </div>
            <div class="p-5">
                @if(count($statusData['data']) > 0)
                <div class="space-y-4">
                    @php
                        $totalStatus = array_sum($statusData['data']);
                    @endphp
                    @foreach($statusData['labels'] as $idx => $status)
                    @php
                        $count = $statusData['data'][$idx];
                        $pct = $totalStatus > 0 ? round(($count / $totalStatus) * 100) : 0;
                        $color = match(strtolower($status)) {
                            'Pending' => 'bg-amber-400',
                            'Processing' => 'bg-blue-400',
                            'Shipped' => 'bg-teal-400',
                            'Delivered' => 'bg-green-500',
                            'Cancelled' => 'bg-red-400',
                            default => 'bg-gray-400',
                        };
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $status }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }}</span>
                                <span class="text-xs text-gray-400">{{ $pct }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-white/5 rounded-full h-2">
                            <div class="{{ $color }} h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-400">
                    <x-heroicon-o-clipboard-document-list class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p class="text-sm">No orders yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- TOP PRODUCTS TABLE --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 mb-8">
        <div class="p-5 border-b border-gray-100 dark:border-white/10">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Selling Products</h3>
            <p class="text-sm text-gray-500 mt-0.5">Products ranked by total revenue</p>
        </div>
        @if(count($topProducts) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/10">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Units Sold</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $idx => $product)
                    <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-400">{{ $idx + 1 }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $product['name'] }}</td>
                        <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">{{ $product['sku'] }}</td>
                        <td class="px-5 py-3.5 text-right text-gray-600">${{ number_format($product['price'], 2) }}</td>
                        <td class="px-5 py-3.5 text-right font-semibold text-gray-900 dark:text-white">{{ $product['total_sold'] }}</td>
                        <td class="px-5 py-3.5 text-right text-gray-500">{{ $product['order_count'] }}</td>
                        <td class="px-5 py-3.5 text-right font-semibold text-success-600">${{ number_format($product['total_revenue'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 text-gray-400">
            <x-heroicon-o-trophy class="w-12 h-12 mx-auto mb-3 opacity-50" />
            <p class="text-sm">No product sales yet</p>
        </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CUSTOMER GEOGRAPHY + RECENT ORDERS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Customer Geography --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
            <div class="p-5 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Geography</h3>
                <p class="text-sm text-gray-500 mt-0.5">Orders and revenue by country</p>
            </div>
            <div class="p-5">
                @if(count($geoData['labels']) > 0)
                <div class="space-y-3">
                    @php
                        $maxGeoRevenue = max($geoData['revenue']);
                    @endphp
                    @foreach($geoData['labels'] as $idx => $country)
                    @php
                        $pct = $maxGeoRevenue > 0 ? round(($geoData['revenue'][$idx] / $maxGeoRevenue) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $country }}</span>
                                <span class="text-xs text-gray-400">{{ $geoData['orders'][$idx] }} orders</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($geoData['revenue'][$idx], 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-white/5 rounded-full h-1.5">
                            <div class="bg-primary-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-400">
                    <x-heroicon-o-globe-alt class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p class="text-sm">No geographic data yet</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="fi-card bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10">
            <div class="p-5 border-b border-gray-100 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
                <p class="text-sm text-gray-500 mt-0.5">Latest 10 orders across the store</p>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-white/10">
                @forelse($recentOrders as $order)
                <div class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order['order_number'] }}</span>
                            @php
                                $statusColor = match($order['status']) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'info',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $order['shipping_first_name'] }} {{ $order['shipping_last_name'] }}
                            · {{ $order['shipping_city'] }}, {{ $order['shipping_country'] }}
                            · {{ count($order['items'] ?? []) }} item{{ count($order['items'] ?? []) !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0 ml-4">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($order['total'], 2) }}</div>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">
                    <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-3 opacity-50" />
                    <p class="text-sm">No orders yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CHART.JS SCRIPTS --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');

            // ── Sales Over Time ──
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: @json($salesData['labels']),
                        datasets: [
                            {
                                label: 'Revenue ($)',
                                data: @json($salesData['revenue']),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                yAxisID: 'y',
                            },
                            {
                                label: 'Orders',
                                data: @json($salesData['orders']),
                                borderColor: 'rgb(99, 102, 241)',
                                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 5,
                                borderDash: [4, 4],
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12 } } },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                padding: 12,
                                titleFont: { size: 13 },
                                bodyFont: { size: 12 },
                                callbacks: {
                                    label: function(ctx) {
                                        if (ctx.dataset.label === 'Revenue ($)') return ' Revenue: $' + ctx.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                                        return ' Orders: ' + ctx.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11 }, maxTicksLimit: 15 } },
                            y: { position: 'left', grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { callback: v => '$' + v.toLocaleString(), font: { size: 11 } } },
                            y1: { position: 'right', grid: { drawOnChartArea: false }, ticks: { font: { size: 11 } } },
                        }
                    }
                });
            }

            // ── Revenue by Category (Doughnut) ──
            const catCtx = document.getElementById('categoryChart');
            if (catCtx) {
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($categoryData['labels']),
                        datasets: [{
                            data: @json($categoryData['data']),
                            backgroundColor: @json($categoryData['colors']),
                            borderWidth: 0,
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '60%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, font: { size: 12 } } },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                        return ' $' + ctx.parsed.toLocaleString(undefined, {minimumFractionDigits: 2}) + ' (' + pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .fi-card { transition: box-shadow 0.2s ease; }
        .fi-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        canvas { max-height: 350px; }
    </style>
    @endpush
</x-filament-panels::page>
