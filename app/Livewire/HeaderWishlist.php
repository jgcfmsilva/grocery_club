<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HeaderWishlist extends Component
{
    public function handleWishlist()
    {
        if (Auth::check()) {
            return redirect()->route('wishlist.index');
        } else {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.layouts.header.header-wishlist');
    }
}
