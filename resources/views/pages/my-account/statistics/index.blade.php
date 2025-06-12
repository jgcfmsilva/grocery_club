@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Statistics</h3>

    @php
        $hasData = $orderCount > 0;
        $hasProductStats = !empty($productStats) && count($productStats) > 0;
        $hasCategoryStats = !empty($categoryStats) && count($categoryStats) > 0;
        $hasMonthStats = !$byMonth->isEmpty();
        $hasYearStats = !$byYear->isEmpty();

        // Virtual Card
        $card = auth()->user()->card ?? null;
        $cardOps = $card ? $card->operations()->orderByDesc('created_at')->limit(10)->get() : collect();
        $hasCard = $card !== null;
        $hasCardOps = $hasCard && $cardOps->count() > 0;
        $cardTotalCredit = $hasCard ? $card->operations()->where('type', 'credit')->sum('value') : 0;
        $cardTotalDebit = $hasCard ? $card->operations()->where('type', 'debit')->sum('value') : 0;
        $cardTotalOps = $hasCard ? $card->operations()->count() : 0;
        $cardLastOp = $hasCard ? $card->operations()->orderByDesc('created_at')->first() : null;
    @endphp

    @if(!$hasData)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded mb-8 text-center">
            <i class="fas fa-info-circle mr-2"></i>
            No statistics available yet. Make some purchases to see your statistics here!
        </div>
    @else
        <div class="mb-6">
            <a href="{{ route('my-account.statistics.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-semibold flex items-center gap-2 justify-center">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-br from-blue-100 via-blue-50 to-white rounded-xl shadow p-6 flex flex-col items-center justify-center">
                <h4 class="font-semibold mb-4 text-lg text-blue-900 flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i> General Statistics
                </h4>
                <div class="w-full grid grid-cols-2 gap-4">
                    <div class="bg-blue-200 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-blue-900 font-medium mb-1">Total Spent</div>
                        <div class="text-2xl font-bold text-blue-900">{{ number_format($totalSpent, 2, ',', '.') }}€</div>
                    </div>
                    <div class="bg-yellow-200 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-yellow-800 font-medium mb-1">Average Order</div>
                        <div class="text-2xl font-bold text-yellow-800">{{ number_format($averageOrder, 2, ',', '.') }}€</div>
                    </div>
                    <div class="bg-orange-200 rounded-lg p-4 flex flex-col items-center shadow col-span-2 md:col-span-1">
                        <div class="text-xs text-orange-800 font-medium mb-1">Min Order</div>
                        <div class="text-2xl font-bold text-orange-800">{{ number_format($minOrder, 2, ',', '.') }}€</div>
                    </div>
                    <div class="bg-purple-200 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-purple-800 font-medium mb-1">Max Order</div>
                        <div class="text-2xl font-bold text-purple-800">{{ number_format($maxOrder, 2, ',', '.') }}€</div>
                    </div>
                    <div class="bg-indigo-200 rounded-lg p-4 flex flex-col items-center shadow col-span-2 md:col-span-1">
                        <div class="text-xs text-indigo-800 font-medium mb-1">Most Purchased Category</div>
                        <div class="text-base font-bold text-indigo-800">
                            {{ $mostCategory ? $mostCategory['category']->name . ' (' . $mostCategory['quantity'] . ')' : '-' }}
                        </div>
                    </div>
                    <div class="bg-red-200 rounded-lg p-4 flex flex-col items-center shadow col-span-2 md:col-span-1">
                        <div class="text-xs text-red-800 font-medium mb-1">Most Purchased Product</div>
                        <div class="text-base font-bold text-red-800">
                            {{ $mostProduct ? $mostProduct['product']->name . ' (' . $mostProduct['quantity'] . ')' : '-' }}
                        </div>
                    </div>
                    <div class="bg-green-200 rounded-lg p-4 flex flex-col items-center shadow">
                        <div class="text-xs text-green-900 font-medium mb-1">Number of Orders</div>
                        <div class="text-2xl font-bold text-green-900">{{ $orderCount }}</div>
                    </div>
                    <div class="bg-gray-300 rounded-lg p-4 flex flex-col items-center shadow col-span-2 md:col-span-1">
                        <div class="text-xs text-gray-800 font-medium mb-1">Total Discounts</div>
                        <div class="text-base font-bold text-gray-800">
                            {{ $totalDiscounts > 0 ? number_format($totalDiscounts, 2, ',', '.') . '€' : '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-center justify-center">
                <canvas id="ordersByMonthChart" height="120"></canvas>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h4 class="font-semibold mb-2">Product Variety</h4>
                @if(!$hasProductStats)
                    <div class="text-gray-500 text-center py-8">No product statistics available.</div>
                @else
                    <canvas id="productsPieChart" height="120"></canvas>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h4 class="font-semibold mb-2">Product Quantities</h4>
                @if(!$hasProductStats)
                    <div class="text-gray-500 text-center py-8">No product statistics available.</div>
                @else
                    <canvas id="productsBarChart" height="120"></canvas>
                @endif
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow p-6">
                <h4 class="font-semibold mb-2">Category Variety</h4>
                @if(!$hasCategoryStats)
                    <div class="text-gray-500 text-center py-8">No category statistics available.</div>
                @else
                    <canvas id="categoriesDoughnutChart" height="120"></canvas>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h4 class="font-semibold mb-2">Category Quantities</h4>
                @if(!$hasCategoryStats)
                    <div class="text-gray-500 text-center py-8">No category statistics available.</div>
                @else
                    <canvas id="categoriesBarChart" height="120"></canvas>
                @endif
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <h4 class="font-semibold mb-4 text-lg text-blue-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> Orders by Month
                </h4>
                @if($hasMonthStats)
                    <canvas id="byMonthLineChart" height="120"></canvas>
                    <table class="min-w-full text-xs text-center border mt-6 bg-blue-50 rounded-lg shadow">
                        <thead class="bg-blue-200">
                            <tr>
                                <th class="px-2 py-1">Month</th>
                                <th class="px-2 py-1">Total</th>
                                <th class="px-2 py-1">Orders</th>
                                <th class="px-2 py-1">Average</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byMonth as $month => $data)
                                <tr>
                                    <td class="px-2 py-1 font-semibold">{{ $month }}</td>
                                    <td class="px-2 py-1 text-blue-900 font-bold">{{ number_format($data['total'], 2, ',', '.') }}€</td>
                                    <td class="px-2 py-1">{{ $data['count'] }}</td>
                                    <td class="px-2 py-1">{{ number_format($data['average'], 2, ',', '.') }}€</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-gray-500 text-center py-8">No monthly statistics available.</div>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <h4 class="font-semibold mb-4 text-lg text-green-800 flex items-center gap-2">
                    <i class="fas fa-calendar"></i> Orders by Year
                </h4>
                @if($hasYearStats)
                    <canvas id="byYearBarChart" height="120"></canvas>
                    <table class="min-w-full text-xs text-center border mt-6 bg-green-50 rounded-lg shadow">
                        <thead class="bg-green-200">
                            <tr>
                                <th class="px-2 py-1">Year</th>
                                <th class="px-2 py-1">Total</th>
                                <th class="px-2 py-1">Orders</th>
                                <th class="px-2 py-1">Average</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($byYear as $year => $data)
                                <tr>
                                    <td class="px-2 py-1 font-semibold">{{ $year }}</td>
                                    <td class="px-2 py-1 text-green-900 font-bold">{{ number_format($data['total'], 2, ',', '.') }}€</td>
                                    <td class="px-2 py-1">{{ $data['count'] }}</td>
                                    <td class="px-2 py-1">{{ number_format($data['average'], 2, ',', '.') }}€</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-gray-500 text-center py-8">No yearly statistics available.</div>
                @endif
            </div>
        </div>

        <div class="mb-8 gap-8">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                <h4 class="font-semibold mb-8 text-lg text-indigo-800 flex items-center gap-2">
                    <i class="fas fa-credit-card"></i> Virtual Card Statistics
                </h4>
                @if(!$hasCard)
                    <div class="text-gray-500 text-center py-8">No virtual card found.</div>
                @else
                    <div class="w-full grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-indigo-200 rounded-lg p-4 flex flex-col items-center shadow">
                            <div class="text-xs text-gray-800 font-medium mb-1">Current Balance</div>
                            <div class="text-xl font-bold text-indigo-800">{{ number_format($card->balance, 2, ',', '.') }}€</div>
                        </div>
                        <div class="bg-green-200 rounded-lg p-4 flex flex-col items-center shadow">
                            <div class="text-xs text-gray-800 font-medium mb-1">Total Credits</div>
                            <div class="text-xl font-bold text-green-700">{{ number_format($cardTotalCredit, 2, ',', '.') }}€</div>
                        </div>
                        <div class="bg-red-200 rounded-lg p-4 flex flex-col items-center shadow">
                            <div class="text-xs text-gray-800 font-medium mb-1">Total Debits</div>
                            <div class="text-xl font-bold text-red-700">{{ number_format($cardTotalDebit, 2, ',', '.') }}€</div>
                        </div>
                        <div class="bg-yellow-200 rounded-lg p-4 flex flex-col items-center shadow">
                            <div class="text-xs text-gray-800 font-medium mb-1">Transactions</div>
                            <div class="text-xl font-bold text-yellow-700">{{ $cardTotalOps }}</div>
                        </div>
                    </div>
                    <div class="w-full text-xs text-gray-500 mb-4 text-center">
                        Last Transaction: <span class="font-semibold text-gray-700">{{ $cardLastOp ? $cardLastOp->created_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="w-full flex flex-col md:flex-row gap-6 mb-2">
                        <div class="flex-1 bg-white rounded-xl shadow p-4 flex items-center justify-center min-h-[180px]">
                            @if($hasCardOps)
                                <div class="w-full h-full" style="min-width:0;">
                                    <canvas id="cardTypePieChart" height="120" style="width:100%;max-width:100%;"></canvas>
                                </div>
                            @else
                                <div class="text-gray-400 text-center w-full py-8 border-2 border-gray-500 border-dashed">
                                    No virtual card transactions found.
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 bg-white rounded-xl shadow p-4 flex items-center justify-center min-h-[180px]">
                            @if($hasCardOps)
                                <div class="w-full h-full" style="min-width:0;">
                                    <canvas id="cardStatsBarChart" height="180" style="width:100%;max-width:100%;"></canvas>
                                </div>
                            @else
                                <div class="text-gray-400 text-center w-full py-8 border-2 border-gray-500 border-dashed">
                                    No virtual card transactions found.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-full mt-8 bg-white rounded-xl shadow p-4">
                        <h5 class="font-semibold mb-8 text-indigo-800 flex items-center gap-2">
                            <i class="fas fa-chart-area"></i> Balance Evolution (Last Transactions)
                        </h5>
                        @if(empty($cardBalanceEvolutionLabels) || empty($cardBalanceEvolutionData))
                            <div class="text-gray-500 text-center py-8 border-2 border-gray-500 border-dashed">
                                No virtual card transactions found.
                            </div>
                        @else
                            <canvas id="cardBalanceEvolutionChart" height="120"></canvas>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            function extractNames(arr, key, limit) {
                limit = limit || 10;
                return arr.slice(0, limit).map(function(stat) { return stat[key].name; });
            }
            function extractQuantities(arr, limit) {
                limit = limit || 10;
                return arr.slice(0, limit).map(function(stat) { return stat['quantity']; });
            }

            var CHART_LIMIT = 10;
            var CATEGORY_QUANTITIES_MAX = 20;

            // Orders by Month (Bar)
            @if(!$byMonth->isEmpty())
            if(document.getElementById('ordersByMonthChart')) {
                new Chart(document.getElementById('ordersByMonthChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_slice(array_keys($byMonth->toArray()), 0, 10)) !!},
                        datasets: [{
                            label: 'Total Spent (€)',
                            data: {!! json_encode(array_slice(array_values($byMonth->pluck('total')->toArray()), 0, 10)) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
            // By Month Line Chart
            if(document.getElementById('byMonthLineChart')) {
                new Chart(document.getElementById('byMonthLineChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($byMonth->toArray())) !!},
                        datasets: [{
                            label: 'Total Spent (€)',
                            data: {!! json_encode(array_values($byMonth->pluck('total')->toArray())) !!},
                            fill: true,
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            borderColor: 'rgba(59,130,246,1)',
                            pointBackgroundColor: 'rgba(59,130,246,1)',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: true } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
            @endif

            // By Year Bar Chart
            @if(!$byYear->isEmpty())
            if(document.getElementById('byYearBarChart')) {
                new Chart(document.getElementById('byYearBarChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($byYear->toArray())) !!},
                        datasets: [{
                            label: 'Total Spent (€)',
                            data: {!! json_encode(array_values($byYear->pluck('total')->toArray())) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: true } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
            @endif

            // Products Pie Chart (Variety)
            @if($hasProductStats)
            if(document.getElementById('productsPieChart') && {!! count($productStats) !!} > 0) {
                const productStats = {!! json_encode(array_values($productStats)) !!};
                new Chart(document.getElementById('productsPieChart').getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: extractNames(productStats, 'product', CHART_LIMIT),
                        datasets: [{
                            label: 'Product Variety',
                            data: extractQuantities(productStats, CHART_LIMIT),
                            backgroundColor: [
                                '#6366f1', '#f59e42', '#10b981', '#ef4444', '#fbbf24', '#3b82f6', '#a21caf', '#14b8a6', '#eab308', '#f472b6'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
            // Products Bar Chart (Quantities)
            if(document.getElementById('productsBarChart') && {!! count($productStats) !!} > 0) {
                const productStats = {!! json_encode(array_values($productStats)) !!};
                new Chart(document.getElementById('productsBarChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: extractNames(productStats, 'product', CHART_LIMIT),
                        datasets: [{
                            label: 'Quantity',
                            data: extractQuantities(productStats, CHART_LIMIT),
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        indexAxis: 'y',
                        scales: { x: { beginAtZero: true } }
                    }
                });
            }
            @endif

            // Categories Doughnut Chart (Variety)
            @if($hasCategoryStats)
            if(document.getElementById('categoriesDoughnutChart') && {!! count($categoryStats) !!} > 0) {
                const categoryStats = {!! json_encode(array_values($categoryStats)) !!};
                new Chart(document.getElementById('categoriesDoughnutChart').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: extractNames(categoryStats, 'category', CHART_LIMIT),
                        datasets: [{
                            label: 'Category Variety',
                            data: extractQuantities(categoryStats, CHART_LIMIT),
                            backgroundColor: [
                                '#f59e42', '#6366f1', '#10b981', '#ef4444', '#fbbf24', '#3b82f6', '#a21caf', '#14b8a6', '#eab308', '#f472b6'
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
            // Categories Horizontal Bar Chart (Quantities)
            if(document.getElementById('categoriesBarChart') && {!! count($categoryStats) !!} > 0) {
                const categoryStats = {!! json_encode(array_values($categoryStats)) !!};
                new Chart(document.getElementById('categoriesBarChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: extractNames(categoryStats, 'category', CATEGORY_QUANTITIES_MAX),
                        datasets: [{
                            label: 'Quantity',
                            data: extractQuantities(categoryStats, CATEGORY_QUANTITIES_MAX),
                            backgroundColor: 'rgba(245, 158, 66, 0.7)',
                            borderColor: 'rgba(245, 158, 66, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        indexAxis: 'y',
                        scales: { x: { beginAtZero: true } }
                    }
                });
            }
            @endif

            // Virtual Card Pie Chart (Credits vs Debits)
            @if($hasCard && $hasCardOps)
                (function() {
                    const ops = @json($cardOps->reverse()->values());
                    let credit = 0, debit = 0;
                    ops.forEach(op => {
                        if (op.type === 'credit' || (op.type && op.type.value === 'credit')) credit += parseFloat(op.value);
                        else if (op.type === 'debit' || (op.type && op.type.value === 'debit')) debit += parseFloat(op.value);
                    });
                    const ctx = document.getElementById('cardTypePieChart');
                    if (ctx) {
                        new Chart(ctx.getContext('2d'), {
                            type: 'pie',
                            data: {
                                labels: ['Credits (€)', 'Debits (€)'],
                                datasets: [{
                                    data: [credit, debit],
                                    backgroundColor: ['#10b981', '#ef4444'],
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: { position: 'bottom' },
                                    title: { display: true, text: 'Credits vs Debits' }
                                }
                            }
                        });
                    }
                })();

                // Virtual Card Stats Bar Chart (Current Balance, Total Credits, Total Debits, Number of Transactions)
                (function() {
                    const ctx = document.getElementById('cardStatsBarChart');
                    if (ctx) {
                        new Chart(ctx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: [
                                    'Current Balance (€)',
                                    'Total Credits (€)',
                                    'Total Debits (€)',
                                    'Number of Transactions'
                                ],
                                datasets: [{
                                    label: 'Virtual Card Stats',
                                    data: [
                                        {{ $card->balance ?? 0 }},
                                        {{ $cardTotalCredit ?? 0 }},
                                        {{ $cardTotalDebit ?? 0 }},
                                        {{ $cardTotalOps ?? 0 }}
                                    ],
                                    backgroundColor: [
                                        '#6366f1',
                                        '#10b981',
                                        '#ef4444',
                                        '#fbbf24'
                                    ],
                                    borderColor: [
                                        '#6366f1',
                                        '#10b981',
                                        '#ef4444',
                                        '#fbbf24'
                                    ],
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    maxBarThickness: 40,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    title: { display: true, text: 'Virtual Card Overview' }
                                },
                                scales: {
                                    y: { beginAtZero: true, ticks: { font: { size: 13 } } },
                                    x: { ticks: { font: { size: 13 } } }
                                }
                            }
                        });
                    }
                })();

                // Virtual Card Balance Evolution Chart
                (function() {
                    const labels = {!! json_encode($cardBalanceEvolutionLabels ?? []) !!};
                    const data = {!! json_encode($cardBalanceEvolutionData ?? []) !!};
                    const ctx = document.getElementById('cardBalanceEvolutionChart');
                    if (ctx && labels.length > 0 && data.length > 0) {
                        new Chart(ctx.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Balance (€)',
                                    data: data,
                                    fill: true,
                                    backgroundColor: 'rgba(99,102,241,0.08)',
                                    borderColor: 'rgba(99,102,241,1)',
                                    pointBackgroundColor: 'rgba(99,102,241,1)',
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: true },
                                    title: { display: false }
                                },
                                scales: {
                                    y: { beginAtZero: true }
                                }
                            }
                        });
                    }
                })();
            @endif

        </script>
    @endif
@endsection
