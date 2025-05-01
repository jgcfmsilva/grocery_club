<?php

use App\Enums\UserType;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

# Get user id
if (!function_exists('userID')) {
    function userID()
    {
        return Auth::check() ? Auth::id() : null;
    }
}


# Calculate shipping cost based on order total.

if (! function_exists('calculateShippingCost')) {
    function calculateShippingCost(float $total): float
    {
        if ($total > 100) {
            return 0; // Portes grátis para pedidos acima de 100€
        } elseif ($total > 50) {
            return 5; // 5€ para pedidos entre 50€ e 100€
        }
        return 10; // 10€ para pedidos abaixo de 50€
    }
}

if (! function_exists('authUser')) {
    function authUser(): User
    {
        return Auth::user();
    }
}

// Calculate price with discount if quantity equal or higher the minimum required (if have discount)
if (! function_exists('calculate_discounted_price')) {
    function calculate_discounted_price($price, $discount, $quantity, $discount_min_qty)
    {
        if ($quantity >= $discount_min_qty && $discount > 0) {
            return $price - $discount;
        }

        return $price;
    }
}

// Calculate price with discount
if (! function_exists('calculate_price_with_discount')) {
    function calculate_price_with_discount($price, $discount)
    {
        if ($discount) {
            return $price - $discount;
        }

        return $price;
    }
}

# Is customer
if (!function_exists('isEmployee')) {
    function isEmployee()
    {
        return Auth::user()->user_type === UserType::Employee;
    }
}

# Is admin
if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return Auth::user()->user_type === UserType::Board;
    }
}

# Clear cache
if (!function_exists('cacheClear')) {
    function cacheClear()
    {
        try {
            Artisan::call('cache:forget spatie.permission.cache');
        } catch (\Throwable $th) {
            //throw $th;
        }

        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
    }
}

# Clear session cache
if (!function_exists('clearOrderSession')) {
    function clearOrderSession()
    {
        session()->forget('payment_method');
        session()->forget('payment_type');
        session()->forget('order_code');
    }
}

if (!function_exists('csrfToken')) {
    #  Get the CSRF token value.
    function csrfToken()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }
        throw new RuntimeException('Session store not set.');
    }
}
