<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartPage extends Component
{

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public $cart;
    public $subtotal = 0;
    public $discount = 0;
    public $shipping = 0;
    public $grandTotal = 0;

    // user info
    public $nif;
    public $default_delivery_address;

    public function mount()
    {
        // gets the user info
        $user = Auth::user();

        if($user != null) {
            $this -> nif = $user->nif;
            $this -> default_delivery_address = $user->default_delivery_address;

        }

        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []);

        foreach ($this->cart as $id => &$item) {
            $product = Product::find($item['id']);

            if ($product) {
                $item['stock'] = $product->stock;
            } else {
                $item['stock'] = 0;
            }
        }

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->discount = 0;
        $this->shipping = 0;
        $this->grandTotal = 0;

        if (empty($this->cart)) {
            return;
        }

        foreach ($this->cart as $item) {
            $originalPrice = $item['price'];

            $discountedPrice = calculate_discounted_price(
                $item['price'],
                $item['discount'] ?? 0,
                $item['quantity'],
                $item['discount_min_qty'] ?? PHP_INT_MAX
            );

            $this->discount += ($originalPrice - $discountedPrice) * $item['quantity'];
            $this->subtotal += $discountedPrice * $item['quantity'];
        }

        $this->shipping = calculateShippingCost($this->subtotal);

        $this->grandTotal = $this->subtotal + $this->shipping;
    }

    public function increment($itemId)
    {
        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['quantity']++;
            $this->updateCart();
        }
    }

    public function decrement($itemId)
    {
        if (isset($this->cart[$itemId])) {
            if ($this->cart[$itemId]['quantity'] > 1) {
                $this->cart[$itemId]['quantity']--;
            } else {
                $this->removeItem($itemId);
                return;
            }
            $this->updateCart();
        }
    }

    public function removeItem($itemId)
    {
        if (isset($this->cart[$itemId])) {
            unset($this->cart[$itemId]);
            $this->updateCart();
        }
    }

    public function updateCart()
    {
        session()->put('cart', $this->cart);
        $this->calculateTotals();
        $this->dispatch('headerCartUpdated');
    }

    public function render()
    {
        return view('livewire.cart.cart-page');
    }
}
