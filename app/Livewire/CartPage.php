<?php

namespace App\Livewire;

use Livewire\Component;

class CartPage extends Component
{
    public function render()
    {
        $cart = session() -> get('cart', []);
        return view('livewire.cart.cart-page', [
            'cart' => $cart
        ]);
    }

    public function decrement(){}
}
