<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Enums\OrderStatus;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets; // Add this import
use Illuminate\Support\Collection;
use App\Models\CardOperation;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Orders do utilizador autenticado
        $orders = Order::with(['items.product.category'])
            ->where('member_id', $user->id)
            ->where('status', OrderStatus::COMPLETED)
            ->get();

        // Estatísticas globais
        $totalSpent = $orders->sum('total');
        $orderCount = $orders->count();
        $averageOrder = $orderCount > 0 ? $totalSpent / $orderCount : 0;
        $maxOrder = $orders->max('total');
        $minOrder = $orders->min('total');

        // Total de descontos obtidos
        $totalDiscounts = 0;
        if ($orders->count() > 0) {
            $totalDiscounts = $orders->sum(function($order) {
                return method_exists($order, 'calculate_order_total_discount')
                    ? $order->calculate_order_total_discount()
                    : 0;
            });
        }

        // Por produto
        $productStats = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if (!$product) continue;
                $pid = $product->id;
                if (!isset($productStats[$pid])) {
                    $productStats[$pid] = [
                        'product' => $product,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }
                $productStats[$pid]['quantity'] += $item->quantity ?? 1;
                $productStats[$pid]['total'] += ($item->quantity ?? 1) * ($item->price ?? $product->price);
            }
        }

        // Most purchased product
        $mostProduct = null;
        if (!empty($productStats)) {
            $mostProduct = collect($productStats)->sortByDesc('quantity')->first();
        }

        // Por categoria
        $categoryStats = [];
        foreach ($productStats as $stat) {
            $cat = $stat['product']->category;
            if ($cat) {
                $cid = $cat->id;
                if (!isset($categoryStats[$cid])) {
                    $categoryStats[$cid] = [
                        'category' => $cat,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }
                $categoryStats[$cid]['quantity'] += $stat['quantity'];
                $categoryStats[$cid]['total'] += $stat['total'];
            }
        }

        // Most purchased category
        $mostCategory = null;
        if (!empty($categoryStats)) {
            $mostCategory = collect($categoryStats)->sortByDesc('quantity')->first();
        }

        // Por mês/ano
        $byMonth = $orders->groupBy(function($order) {
            return $order->created_at->format('m-Y');
        })->map(function($orders) {
            return [
                'total' => $orders->sum('total'),
                'count' => $orders->count(),
                'average' => $orders->avg('total'),
            ];
        });

        $byYear = $orders->groupBy(function($order) {
            return $order->created_at->format('Y');
        })->map(function($orders) {
            return [
                'total' => $orders->sum('total'),
                'count' => $orders->count(),
                'average' => $orders->avg('total'),
            ];
        });

        // Virtual Card Balance Evolution Data (last 10 operations)
        $cardBalanceEvolutionLabels = [];
        $cardBalanceEvolutionData = [];
        $card = $user->card ?? null;

        if ($card) {
            $ops = $card->operations()->orderBy('created_at', 'desc')->take(10)->get();
            $ops = $ops->reverse();
            $running = 0.0;
            $labels = [];
            $balances = [];

            foreach ($ops as $op) {
                $type = $op->type;
                $value = (float) $op->value;

                if ($type->value === 'credit') {
                    $running += $value;
                } elseif ($type->value === 'debit') {
                    $running -= $value;
                }

                $labels[] = $op->created_at ? $op->created_at->format('d/m/Y H:i') : '';
                $balances[] = round($running, 2);
            }

            $cardBalanceEvolutionLabels = $labels;
            $cardBalanceEvolutionData = $balances;
        }

        return view('pages.my-account.statistics.index', compact(
            'totalSpent', 'orderCount', 'averageOrder', 'maxOrder', 'minOrder',
            'byMonth', 'byYear', 'productStats', 'categoryStats', 'totalDiscounts', 'orders',
            'mostProduct', 'mostCategory',
            'cardBalanceEvolutionLabels', 'cardBalanceEvolutionData'
        ));
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $orders = Order::with(['items.product.category'])
            ->where('member_id', $user->id)
            ->where('status', OrderStatus::COMPLETED)
            ->get();

        $totalSpent = $orders->sum('total');
        $orderCount = $orders->count();
        $averageOrder = $orderCount > 0 ? $totalSpent / $orderCount : 0;
        $maxOrder = $orders->max('total');
        $minOrder = $orders->min('total');

        $totalDiscounts = 0;
        if ($orders->count() > 0) {
            $totalDiscounts = $orders->sum(function($order) {
                return method_exists($order, 'calculate_order_total_discount')
                    ? $order->calculate_order_total_discount()
                    : 0;
            });
        }

        $productStats = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                if (!$product) continue;
                $pid = $product->id;
                if (!isset($productStats[$pid])) {
                    $productStats[$pid] = [
                        'product' => $product,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }
                $productStats[$pid]['quantity'] += $item->quantity ?? 1;
                $productStats[$pid]['total'] += ($item->quantity ?? 1) * ($item->price ?? $product->price);
            }
        }

        $mostProduct = null;
        if (!empty($productStats)) {
            $mostProduct = collect($productStats)->sortByDesc('quantity')->first();
        }

        $categoryStats = [];
        foreach ($productStats as $stat) {
            $cat = $stat['product']->category;
            if ($cat) {
                $cid = $cat->id;
                if (!isset($categoryStats[$cid])) {
                    $categoryStats[$cid] = [
                        'category' => $cat,
                        'quantity' => 0,
                        'total' => 0,
                    ];
                }
                $categoryStats[$cid]['quantity'] += $stat['quantity'];
                $categoryStats[$cid]['total'] += $stat['total'];
            }
        }

        $mostCategory = null;
        if (!empty($categoryStats)) {
            $mostCategory = collect($categoryStats)->sortByDesc('quantity')->first();
        }

        $byMonth = $orders->groupBy(function($order) {
            return $order->created_at->format('m-Y');
        })->map(function($orders) {
            return [
                'total' => $orders->sum('total'),
                'count' => $orders->count(),
                'average' => $orders->avg('total'),
            ];
        });

        $byYear = $orders->groupBy(function($order) {
            return $order->created_at->format('Y');
        })->map(function($orders) {
            return [
                'total' => $orders->sum('total'),
                'count' => $orders->count(),
                'average' => $orders->avg('total'),
            ];
        });

        $orderRows = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                $category = $product && $product->category ? $product->category->name : '-';
                $orderRows[] = [
                    'Order ID' => $order->id,
                    'Date' => $order->created_at->format('Y-m-d H:i'),
                    'Product' => $product ? $product->name : '-',
                    'Category' => $category,
                    'Quantity' => $item->quantity ?? 1,
                    'Unit Price' => $item->price ?? ($product ? $product->price : 0),
                    'Total' => ($item->quantity ?? 1) * ($item->price ?? ($product ? $product->price : 0)),
                ];
            }
        }

        $generalStats = [
            ['Metric', 'Value'],
            ['Total Spent', $totalSpent],
            ['Number of Orders', $orderCount],
            ['Average Order', $averageOrder],
            ['Max Order', $maxOrder],
            ['Min Order', $minOrder],
        ];

        $byMonthRows = [['Month', 'Total', 'Orders', 'Average']];
        foreach ($byMonth as $month => $data) {
            $byMonthRows[] = [
                $month,
                $data['total'],
                $data['count'],
                $data['average'],
            ];
        }

        $byYearRows = [['Year', 'Total', 'Orders', 'Average']];
        foreach ($byYear as $year => $data) {
            $byYearRows[] = [
                $year,
                $data['total'],
                $data['count'],
                $data['average'],
            ];
        }

        $byProductRows = [['Product', 'Quantity', 'Total']];
        foreach ($productStats as $stat) {
            $byProductRows[] = [
                $stat['product']->name,
                $stat['quantity'],
                $stat['total'],
            ];
        }

        $byCategoryRows = [['Category', 'Quantity', 'Total']];
        foreach ($categoryStats as $stat) {
            $byCategoryRows[] = [
                $stat['category']->name,
                $stat['quantity'],
                $stat['total'],
            ];
        }

        $orderSummaryRows = [['Order ID', 'Date', 'Total', 'Number of Items']];
        foreach ($orders as $order) {
            $orderSummaryRows[] = [
                $order->id,
                $order->created_at->format('Y-m-d H:i'),
                $order->total,
                $order->items->sum('quantity'),
            ];
        }

        $card = $user->card;
        $transactionRows = [['Date', 'Type', 'Value', 'Payment Type', 'Payment Reference', 'Order ID']];
        if ($card) {
            $operations = $card->operations()->orderBy('created_at')->get();
            foreach ($operations as $op) {
                $typeLabel = '';
                if ($op->type) {
                    if (is_object($op->type) && method_exists($op->type, 'label')) {
                        $typeLabel = $op->type->label();
                    } elseif (is_string($op->type)) {
                        $typeLabel = ucfirst($op->type);
                    }
                }
                $paymentTypeLabel = '';
                if ($op->payment_type) {
                    if (is_object($op->payment_type) && method_exists($op->payment_type, 'label')) {
                        $paymentTypeLabel = $op->payment_type->label();
                    } elseif (is_string($op->payment_type)) {
                        $paymentTypeLabel = $op->payment_type;
                    }
                }
                $transactionRows[] = [
                    $op->created_at ? $op->created_at->format('Y-m-d H:i') : '',
                    $typeLabel,
                    $op->value,
                    $paymentTypeLabel,
                    $op->payment_reference,
                    $op->order_id,
                ];
            }
        }

        $cardStatsRows = [['Metric', 'Value']];
        if ($card) {
            $totalCredit = $card->operations()->where('type', 'credit')->sum('value');
            $totalDebit = $card->operations()->where('type', 'debit')->sum('value');
            $totalOps = $card->operations()->count();
            $lastOp = $card->operations()->orderByDesc('created_at')->first();
            $cardStatsRows[] = ['Current Balance', $card->balance];
            $cardStatsRows[] = ['Total Credits', $totalCredit];
            $cardStatsRows[] = ['Total Debits', $totalDebit];
            $cardStatsRows[] = ['Number of Transactions', $totalOps];
            $cardStatsRows[] = ['Last Transaction', $lastOp ? $lastOp->created_at->format('Y-m-d H:i') : '-'];
        } else {
            $cardStatsRows[] = ['No virtual card found', ''];
        }

        $geralRows[] = ['General Statistics'];
        $geralRows[] = ['Total Spent', $totalSpent];
        $geralRows[] = ['Average Order', $averageOrder];
        $geralRows[] = ['Min Order', $minOrder];
        $geralRows[] = ['Max Order', $maxOrder];
        $geralRows[] = ['Most Purchased Category', $mostCategory ? $mostCategory['category']->name . ' (' . $mostCategory['quantity'] . ')' : '-'];
        $geralRows[] = ['Most Purchased Product', $mostProduct ? $mostProduct['product']->name . ' (' . $mostProduct['quantity'] . ')' : '-'];
        $geralRows[] = ['Number of Orders', $orderCount];
        $geralRows[] = ['Total Discounts', $totalDiscounts];

        $geralRows[] = ["---------------------------------"];

        $geralRows[] = ['Orders by Month'];
        $geralRows[] = ['Month', 'Total Spent', 'Orders', 'Average'];
        foreach ($byMonth as $month => $data) {
            $geralRows[] = [
                $month,
                $data['total'],
                $data['count'],
                $data['average'],
            ];
        }

        $geralRows[] = ["---------------------------------"];

        $geralRows[] = ['Orders by Year'];
        $geralRows[] = ['Year', 'Total Spent', 'Orders', 'Average'];
        foreach ($byYear as $year => $data) {
            $geralRows[] = [
                $year,
                $data['total'],
                $data['count'],
                $data['average'],
            ];
        }

        $geralRows[] = ["---------------------------------"];

        $geralRows[] = ['Top Products (Variety)'];
        $geralRows[] = ['Product', 'Quantity'];
        $productStatsList = array_values($productStats);
        foreach (array_slice($productStatsList, 0, 10) as $stat) {
            $geralRows[] = [
                $stat['product']->name,
                $stat['quantity'],
            ];
        }

        $geralRows[] = ["---------------------------------"];

        $geralRows[] = ['Top Categories (Variety)'];
        $geralRows[] = ['Category', 'Quantity'];
        $categoryStatsList = array_values($categoryStats);
        foreach (array_slice($categoryStatsList, 0, 10) as $stat) {
            $geralRows[] = [
                $stat['category']->name,
                $stat['quantity'],
            ];
        }

        $geralRows[] = ["---------------------------------"];

        $card = $user->card;
        if ($card) {
            $cardTotalCredit = $card->operations()->where('type', 'credit')->sum('value');
            $cardTotalDebit = $card->operations()->where('type', 'debit')->sum('value');
            $cardTotalOps = $card->operations()->count();
            $cardLastOp = $card->operations()->orderByDesc('created_at')->first();

            $geralRows[] = ['Virtual Card Statistics'];
            $geralRows[] = ['Current Balance', $card->balance];
            $geralRows[] = ['Total Credits', $cardTotalCredit];
            $geralRows[] = ['Total Debits', $cardTotalDebit];
            $geralRows[] = ['Number of Transactions', $cardTotalOps];
            $geralRows[] = ['Last Transaction', $cardLastOp ? $cardLastOp->created_at->format('d/m/Y H:i') : '-'];
        }

        $filename = 'my_statistics_' . now()->format('Ymd_His') . '.xlsx';
        $sheets = [
            'Geral' => collect($geralRows),
            'General Stats' => collect($generalStats),
            'Orders Detail' => collect($orderRows ? [array_keys($orderRows[0])] : [['No data']])->concat($orderRows),
            'Orders Summary' => collect($orderSummaryRows),
            'By Month' => collect($byMonthRows),
            'By Year' => collect($byYearRows),
            'By Product' => collect($byProductRows),
            'By Category' => collect($byCategoryRows),
            'Virtual Card Transactions' => collect($transactionRows),
            'Virtual Card Stats' => collect($cardStatsRows),
        ];

        $export = new class($sheets) implements WithMultipleSheets {
            private $sheets;
            public function __construct($sheets) { $this->sheets = $sheets; }
            public function sheets(): array {
                $result = [];
                foreach ($this->sheets as $title => $rows) {
                    $result[] = new class($rows, $title) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithTitle {
                        private $rows;
                        private $title;
                        public function __construct($rows, $title) { $this->rows = $rows; $this->title = $title; }
                        public function collection() { return $this->rows; }
                        public function title(): string { return $this->title; }
                    };
                }
                return $result;
            }
        };

        return Excel::download($export, $filename);
    }
}
