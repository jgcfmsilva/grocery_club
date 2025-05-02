<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class HeaderCartMenu extends Component
{
    protected $listeners = ['headerCartUpdated' => 'updateCart'];

    public $cartItems = [];
    public $subtotal = 0;

    public function mount()
    {
        $cart = session()->get('cart', []);
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cart as $id => &$item) {
            if (isset($products[$id])) {
                $product = $products[$id];
                $item['price'] = $product->price;
                $item['discount'] = $product->discount;
                $item['discount_min_qty'] = $product->discount_min_qty;
                $item['stock'] = $product->stock;
            }
        }

        session()->put('cart', $cart);
        $this->cartItems = $cart;
        $this->calculateSubtotal();
    }

    public function updateCart()
    {
        $this->cartItems = session()->get('cart', []);
        $this->calculateSubtotal();
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $this->cartItems = $cart;
        $this->calculateSubtotal();
        $this->dispatch('cartUpdated');
    }

    public function calculateSubtotal()
    {
        $this->subtotal = 0;

        foreach ($this->cartItems as $item) {
            $discountedPrice = calculate_discounted_price(
                $item['price'],
                $item['discount'],
                $item['quantity'],
                $item['discount_min_qty']
            );

            $this->subtotal += $discountedPrice * $item['quantity'];
        }
    }

    public function render()
    {
        return view('livewire.layouts.header.header-cart-menu');
    }
}
