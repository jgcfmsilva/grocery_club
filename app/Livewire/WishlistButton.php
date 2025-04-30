<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishlistButton extends Component
{
    public $productId;
    public $inWishlist = false;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->inWishlist = in_array($this->productId, authUser()->custom['wishlist'] ?? []);
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
            abort(403, 'You need to be logged in to manage your wishlist!');
        }

        $user = authUser();
        $custom = $user->custom ?? [];
        $custom['wishlist'] = $custom['wishlist'] ?? [];
        $wishlist = $custom['wishlist'] ?? [];

        if (in_array($this->productId, $wishlist)) {
            $wishlist = array_filter($wishlist, fn($id) => $id != $this->productId);
        } else {
            $wishlist[] = $this->productId;
        }

        $custom['wishlist'] = array_values($wishlist);
        $user->custom = $custom;
        $user->save();

        $this->inWishlist = !$this->inWishlist;
        $this->dispatch('wishlistUpdated');
    }

    public function redirectToLogin()
    {
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.wishlist.wishlist-button');
    }
}
