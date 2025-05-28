<?php

use App\Enums\UserType;
use App\Models\Order;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $shippingCost = DB::table('settings_shipping_costs')
            ->where('min_value_threshold', '<=', $total)
            ->where('max_value_threshold', '>', $total)
            ->value('shipping_cost');


        return $shippingCost ?? 0;
    }
}

if (! function_exists('authUser')) {
    function authUser(): User | null
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

// Calculate total discount for an order
if (! function_exists('calculate_order_total_discount')) {
    function calculate_order_total_discount($order)
    {
        // $order can be a model or array with 'items'
        $items = is_object($order) && isset($order->items) ? $order->items : (is_array($order) && isset($order['items']) ? $order['items'] : []);
        $total = 0;
        foreach ($items as $item) {
            // $item can be array or object
            $discount = is_object($item) ? ($item->discount ?? 0) : ($item['discount'] ?? 0);
            $qty = is_object($item) ? ($item->quantity ?? 0) : ($item['quantity'] ?? 0);
            $total += $discount * $qty;
        }
        return $total;
    }
}

// Calculate percentage of discount
if (! function_exists('calculate_percentage_discount')) {
    function calculate_percentage_discount($originalPrice, $discountedPrice)
    {
        if ($originalPrice > 0 && $discountedPrice < $originalPrice) {
            $discount = $originalPrice - $discountedPrice;
            return ($discount / $originalPrice) * 100;
        }

        return 0;
    }
}

# Is employee
if (!function_exists('isPendingMember')) {
    function isPendingMember()
    {
        return authUser()->isPendingMember();
    }
}

# Is active member
if (!function_exists('isMember')) {
    function isMember()
    {
        return authUser()->isMember();
    }
}

# Is employee
if (!function_exists('isEmployee')) {
    function isEmployee()
    {
        return authUser()->isEmployee();
    }
}

# Is admin
if (!function_exists('isBoard')) {
    function isBoard()
    {
        return authUser()->isBoard();
    }
}

# Is board or active member
if (!function_exists('isActiveMember')) {
    function isActiveMember()
    {
        return authUser()->isActiveMember();
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

if (!function_exists('updateOrderTotals')) {
    function updateOrderTotals(Order $order)
    {
        $order->load('items');

        $totalItems = $order->items->sum('subtotal');
        $shippingCost = calculateShippingCost($totalItems);
        $total = $totalItems + $shippingCost;

        $order->update([
            'total_items' => $totalItems,
            'shipping_cost' => $shippingCost,
            'total' => $total
        ]);
    }
}
