<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductCard extends Component
{
    public $product;

    // Recebe o produto para ser passado ao componente
    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('components.product-card');
    }
}
