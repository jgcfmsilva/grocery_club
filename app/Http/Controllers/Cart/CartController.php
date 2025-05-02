<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function show(){
        // gets the cart
        $cart = session()->get('cart', []);

        if(empty($cart)):
           // redirects
           return redirect() -> route('products.index');
        else:
            // shows the view
            return view('pages.cart.index');
        endif;
    }
}
