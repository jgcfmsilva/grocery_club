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

        if ($product->stock < $this->quantity) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->warning("Only {$product->stock} x {$product->name} available in stock. You can still place the order.");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$this->productId])) {
            $cart[$this->productId]['quantity'] += $this->quantity;
        } else {
            $cart[$this->productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount_min_qty' => $product->discount_min_qty,
                'discount' => $product->discount,
                'quantity' => $this->quantity,
                'photo' => $product->photo,
                'category_name' => $product->category?->name ?? 'Uncategorized',
                'stock' => $product->stock,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('headerCartUpdated');

        flash()
        ->option('position', 'bottom-right')
        ->option('timeout', 3000)
        ->success("{$this->quantity} x {$product->name} added to cart!");
    }

    public function render()
    {
        return view('livewire.products.add-to-cart');
    }
}
