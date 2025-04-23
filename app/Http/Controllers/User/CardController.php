<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return view('pages.my-account.virtual-card.index');
    }

    public function showTopUp()
    {
        return view('pages.my-account.virtual-card.topup');
    }

    public function topUpCard(Request $request)
    {
        // Vai ser implementado mais tarde
    }
}
