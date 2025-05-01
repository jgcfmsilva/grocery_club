<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CardOperation;

class TransactionController extends Controller
{

    public function index()
    {
        $cardId = authUser()->card->id;

        $operations = CardOperation::with('order')
        ->where('card_id', $cardId)
        ->orderByDesc('created_at')
        ->get();

        return view('pages.my-account.transactions.index', compact('operations'));
    }
}
