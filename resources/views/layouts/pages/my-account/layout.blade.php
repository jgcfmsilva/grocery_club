@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <!-- Header com Saudação e Logout -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Hello, {{ Auth::user()->name }}</h2>
            </div>
            <div class="ms-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link p-1"
                            style="color: var(--bs-secondary); transition: color 0.3s;"
                            onmouseover="this.style.color='var(--bs-background)'" 
                            onmouseout="this.style.color='var(--bs-secondary)'">
                        Log out
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar com Card -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="list-group rounded-3 list-group-flush">
                            <a href="{{ route('my-account.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account') ? 'active' : '' }}">
                                <i class="bi bi-person-fill me-2"></i> Personal Data
                            </a>
                            <a href="{{ route('my-account.orders.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account/orders') ? 'active' : '' }}">
                                <i class="bi bi-box-seam me-2"></i> My Orders
                            </a>
                            <a href="{{ route('my-account.virtual-card.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account/virtual-card') ? 'active' : '' }}">
                                <i class="bi bi-credit-card-2-front me-2"></i> Virtual Card
                            </a>
                            <a href="{{ route('my-account.transactions.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account/transactions') ? 'active' : '' }}">
                                <i class="bi bi-currency-exchange me-2"></i> Transactions
                            </a>
                            <a href="{{ route('my-account.statistics.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account/statistics') ? 'active' : '' }}">
                                <i class="bi bi-graph-up me-2"></i> Statistics
                            </a>
                            <a href="{{ route('my-account.change-password.index') }}" 
                            class="list-group-item list-group-item-action d-flex align-items-center bg-white {{ request()->is('my-account/change-password') ? 'active' : '' }}">
                                <i class="bi bi-shield-lock me-2"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo com Card -->
            <div class="col-md-9">
                <div class="card shadow-sm rounded-3 bg-white p-4">
                    @yield('account-content')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/main.css')
@endpush