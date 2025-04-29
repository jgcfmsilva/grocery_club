<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function index()
    {
        return view('pages.wishlist.index');
    }
}
