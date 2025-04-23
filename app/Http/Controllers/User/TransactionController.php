<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index()
    {
        return view('pages.my-account.transactions.index');
    }

    public function funcao(Request $request)
    {
        // Vai ser implementado mais tarde
    }
}
