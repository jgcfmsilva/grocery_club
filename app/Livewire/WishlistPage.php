<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Livewire\WithPagination;

class WishlistPage extends Component
{  
    use WithPagination;

    protected $listeners = ['wishlistUpdated' => '$refresh'];
    
    public function render()
    {
        $user = Auth::user();
        $wishlistIds = $user->custom['wishlist'] ?? [];

        $products = Product::whereIn('id', $wishlistIds)
                            ->paginate(12);

        return view('livewire.wishlist.wishlist-page', [
            'products' => $products,
        ]);
    }
}

