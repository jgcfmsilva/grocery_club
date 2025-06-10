@extends('layouts.dashboard_app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>
    <div class="bg-white rounded-xl shadow p-8 mb-10 w-full">
        <p class="text-lg mb-4">
            Welcome to the administration dashboard. Here you can manage users, memberships, inventory, virtual cards, products, categories, orders and platform settings.
        </p>
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Business Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Transaction Records -->
                <div class="bg-blue-100 border-2 border-blue-200 rounded-lg p-6 shadow hover:bg-blue-200 transition">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <i class="fas fa-exchange-alt text-blue-600"></i> Transaction Records
                    </h3>
                    <div class="text-3xl font-bold text-blue-700 mb-1">
                        {{ number_format(\App\Models\CardOperation::count()) }}
                    </div>
                    <div class="text-gray-600 text-sm mb-2">
                        Total number of credit and debit operations processed by the platform, including all user payments, top-ups, and refunds.
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('dashboard.virtual-cards.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-xs font-semibold flex items-center gap-1">
                            <i class="fas fa-credit-card"></i>
                            Users Transactions
                        </a>
                        <a href="{{ route('dashboard.virtual-cards.transactions') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-xs font-semibold flex items-center gap-1">
                            <i class="fas fa-list"></i>
                            All Transactions
                        </a>
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        Last transaction: 
                        @php
                            $lastOp = \App\Models\CardOperation::orderByDesc('created_at')->first();
                        @endphp
                        {{ $lastOp ? $lastOp->created_at->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
                <!-- Sales Performance -->
                <div class="bg-green-100 border-2 border-green-200 rounded-lg p-6 shadow hover:bg-green-200 transition">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <i class="fas fa-chart-line text-green-600"></i> Sales Performance
                    </h3>
                    <div class="text-3xl font-bold text-green-700 mb-1">
                        {{ number_format(\App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->sum('total'), 2, ',', '.') }}€
                    </div>
                    <div class="text-gray-600 text-sm mb-2">
                        Total revenue from completed orders. Analyze sales growth, average order value, and sales by period.
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2 text-xs">
                        <div>
                            <span class="font-semibold">Today:</span>
                            {{ number_format(\App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->whereDate('created_at', now())->sum('total'), 2, ',', '.') }}€
                        </div>
                        <div>
                            <span class="font-semibold">This Week:</span>
                            {{ number_format(\App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total'), 2, ',', '.') }}€
                        </div>
                        <div>
                            <span class="font-semibold">This Month:</span>
                            {{ number_format(\App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total'), 2, ',', '.') }}€
                        </div>
                        <div>
                            <span class="font-semibold">This Year:</span>
                            {{ number_format(\App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->whereYear('created_at', now()->year)->sum('total'), 2, ',', '.') }}€
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        Orders completed: {{ \App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->count() }}
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('dashboard.orders.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-xs font-semibold flex items-center gap-1 w-auto min-w-[120px] justify-center">
                            <i class="fas fa-box"></i>
                            View All Orders
                        </a>
                    </div>
                </div>
                <!-- Membership Trends -->
                <div class="bg-purple-100 border-2 border-purple-200 rounded-lg p-6 shadow hover:bg-purple-200 transition flex flex-col">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <i class="fas fa-users text-purple-600"></i> Membership Trends
                    </h3>
                    <div class="flex flex-col md:flex-row gap-6 mb-2">
                        <div class="flex-1">
                            <div class="text-3xl font-bold text-purple-700 mb-1">
                                {{ \App\Models\User::where('type', \App\Enums\UserType::Member)->count() }}
                            </div>
                            <div class="text-gray-600 text-sm mb-2">
                                Active members
                            </div>
                        </div>
                        <div class="flex-1">
                            @php
                                $membershipFee = \DB::table('settings')->value('membership_fee') ?? 0;
                                $membershipsAll = \App\Models\User::where('type', \App\Enums\UserType::Member);
                                $membershipsToday = $membershipsAll->clone()->whereDate('created_at', now())->count();
                                $membershipsWeek = $membershipsAll->clone()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
                                $membershipsMonth = $membershipsAll->clone()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                                $membershipsYear = $membershipsAll->clone()->whereYear('created_at', now()->year)->count();
                                $membershipsTotal = $membershipsAll->clone()->count();
                            @endphp
                            <div class="font-semibold mb-1 text-sm text-purple-800">Membership Revenue</div>
                            <ul class="text-xs text-gray-700 space-y-1">
                                <li>Today: <span class="font-semibold">{{ number_format($membershipsToday * $membershipFee, 2, ',', '.') }}€</span></li>
                                <li>This Week: <span class="font-semibold">{{ number_format($membershipsWeek * $membershipFee, 2, ',', '.') }}€</span></li>
                                <li>This Month: <span class="font-semibold">{{ number_format($membershipsMonth * $membershipFee, 2, ',', '.') }}€</span></li>
                                <li>This Year: <span class="font-semibold">{{ number_format($membershipsYear * $membershipFee, 2, ',', '.') }}€</span></li>
                                <li>All Time: <span class="font-semibold">{{ number_format($membershipsTotal * $membershipFee, 2, ',', '.') }}€</span></li>
                            </ul>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold mb-1 text-sm text-purple-800">New Members</div>
                            <ul class="text-xs text-gray-700 space-y-1">
                                <li>
                                    <span class="inline-block w-24">Today:</span>
                                    <span class="font-semibold">
                                        {{ \App\Models\User::where('type', \App\Enums\UserType::Member)->whereDate('created_at', now())->count() }}
                                    </span>
                                </li>
                                <li>
                                    <span class="inline-block w-24">This Week:</span>
                                    <span class="font-semibold">
                                        {{ \App\Models\User::where('type', \App\Enums\UserType::Member)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                                    </span>
                                </li>
                                <li>
                                    <span class="inline-block w-24">This Month:</span>
                                    <span class="font-semibold">
                                        {{ \App\Models\User::where('type', \App\Enums\UserType::Member)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}
                                    </span>
                                </li>
                                <li>
                                    <span class="inline-block w-24">This Year:</span>
                                    <span class="font-semibold">
                                        {{ \App\Models\User::where('type', \App\Enums\UserType::Member)->whereYear('created_at', now()->year)->count() }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-2 flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('dashboard.memberships.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow text-xs font-semibold flex items-center gap-1 w-full sm:w-auto min-w-[120px] justify-center">
                            <i class="fas fa-id-card"></i>
                            View Memberships
                        </a>
                    </div>
                </div>
                <!-- Other Key Metrics -->
                <div class="bg-orange-100 border-2 border-orange-200 rounded-lg p-6 shadow hover:bg-orange-200 transition">
                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle text-orange-600"></i> Other Key Metrics
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-1 text-xs mb-2">
                        <div>
                            <span class="font-semibold">Pending Members:</span>
                            {{ \App\Models\User::where('type', \App\Enums\UserType::PendingMember)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Blocked Users:</span>
                            {{ \App\Models\User::where('blocked', true)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Products in Stock:</span>
                            {{ \App\Models\Product::where('stock', '>', 0)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Products Out of Stock:</span>
                            {{ \App\Models\Product::where('stock', '<=', 0)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Products Low in Stock:</span>
                            {{ \App\Models\Product::whereColumn('stock', '<=', 'stock_lower_limit')->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Total Products:</span>
                            {{ \App\Models\Product::count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Categories:</span>
                            {{ \App\Models\Category::count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Orders Pending:</span>
                            {{ \App\Models\Order::where('status', \App\Enums\OrderStatus::PENDING)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Orders Completed:</span>
                            {{ \App\Models\Order::where('status', \App\Enums\OrderStatus::COMPLETED)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Orders Canceled:</span>
                            {{ \App\Models\Order::where('status', \App\Enums\OrderStatus::CANCELED)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Total Users:</span>
                            {{ \App\Models\User::count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Employees:</span>
                            {{ \App\Models\User::where('type', \App\Enums\UserType::Employee)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Board Members:</span>
                            {{ \App\Models\User::where('type', \App\Enums\UserType::Board)->count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Total Virtual Cards:</span>
                            {{ \App\Models\Card::count() }}
                        </div>
                        <div>
                            <span class="font-semibold">Total Transactions:</span>
                            {{ \App\Models\CardOperation::count() }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('dashboard.settings.index') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded shadow font-semibold text-xs transition flex items-center gap-1">
                            <i class="fas fa-cog"></i>
                            Go to Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- You can add dashboard widgets or statistics here --}}
    </div>
</div>
@endsection
