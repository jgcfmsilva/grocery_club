<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;
    
    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($this->productId);
        $cart = session()->get('cart', []);
        
        if (isset($cart[$this->productId])) {
            $cart[$this->productId]['quantity'] += $this->quantity;
        } else {
            $cart[$this->productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => calculate_discounted_price(
                    $product->price,
                    $product->discount,
                    $this->quantity,
                    $product->discount_min_qty
                ),
                'discount_min_qty' => $product->discount_min_qty,
                'discount' => $product->discount,
                'quantity' => $this->quantity,
                'photo' => $product->photo,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated');

        $productName = $product->name;
        $quantity = $this->quantity;

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success("{$quantity} x {$productName} added to cart!"); 
    }

    public function render()
    {
        return view('livewire.products.add-to-cart');
    }
}
