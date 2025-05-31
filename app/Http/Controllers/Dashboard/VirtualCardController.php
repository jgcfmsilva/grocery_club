<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;

class VirtualCardController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::with(['user', 'operations']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $sortable = [
            'card_number' => 'card_number',
            'owner' => 'owner',
            'balance' => 'balance',
            'last_transaction' => 'last_transaction',
        ];
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');

        if (array_key_exists($sort, $sortable)) {
            if ($sort === 'owner') {
                $query->join('users', 'cards.id', '=', 'users.id')
                      ->orderBy('users.name', $direction)
                      ->select('cards.*');
            } elseif ($sort === 'last_transaction') {
                $query->withMax('operations', 'created_at');
                $query->orderBy('operations_max_created_at', $direction);
            } else {
                $query->orderBy($sortable[$sort], $direction);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $virtualCards = $query->paginate(20)->appends($request->all());

        return view('pages.dashboard.virtual-cards.index', compact('virtualCards', 'sort', 'direction'));
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
