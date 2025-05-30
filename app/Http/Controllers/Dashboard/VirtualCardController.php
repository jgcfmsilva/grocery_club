<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;

class VirtualCardController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $virtualCards = $query->orderBy('id', 'desc')->paginate(20);
        
        return view('pages.dashboard.virtual-cards.index', compact('virtualCards'));
    }

    public function create()
    {
        return view('pages.dashboard.virtual-cards.create');
    }

    public function show(Card $card)
    {
        $card->load('user', 'operations');
       
        return view('pages.dashboard.virtual-cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        $card->load('user');

        return view('pages.dashboard.virtual-cards.edit', compact('card'));
    }
}
