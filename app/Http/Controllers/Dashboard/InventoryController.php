<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        // Custom sorting logic
        if ($sort === 'category') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $direction)
                  ->select('products.*');
        } elseif ($sort === 'stock_level') {
            $query->orderByRaw('
                CASE
                    WHEN stock <= stock_lower_limit THEN 0
                    WHEN stock >= stock_upper_limit THEN 2
                    ELSE 1
                END ' . ($direction === 'asc' ? 'asc' : 'desc')
            );
        } else {
            $query->orderBy($sort, $direction);
        }

        $products = $query->paginate(20)->appends($request->all());

        return view('pages.dashboard.inventory.index', compact('products'));
    }
}
