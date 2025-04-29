<?php

namespace App\Livewire;

use Livewire\Component;

class HeaderCartMenu extends Component
{
    protected $listeners = ['cartUpdated' => 'updateCart'];

    public $cartItems = [];
    public $subtotal = 0;

    public function mount()
    {
        $this->cartItems = session()->get('cart', []);
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
    }

    public function calculateSubtotal()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += calculate_discounted_price(
                $item['price'],
                $item['discount'],
                $item['quantity'],
                $item['discount_min_qty']
            ) * $item['quantity'];
        }
    }

    public function render()
    {
        return view('livewire.layouts.header.header-cart-menu');
    }
}
