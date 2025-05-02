<?php

namespace App\Livewire;

use Livewire\Component;

class CartPage extends Component
{

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public $cart;
    public $subtotal = 0;
    public $discount = 0;
    public $shipping = 5.99;
    public $grandTotal = 0;

    public function mount()
    {
        $this->loadCart();
        $this->calculateTotals();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []);
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->discount = 0;

        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];        }

        $this->grandTotal = $this->subtotal - $this->discount + $this->shipping;
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
        $this->dispatch('cartUpdated');

        if(count($this->cart) == 0):
            return redirect() -> route('products.index');
        endif;

    }

    public function render()
    {
        return view('livewire.cart.cart-page');
    }
}
