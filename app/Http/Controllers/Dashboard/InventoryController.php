<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockAdjustment;

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

        // Stock filter logic
        if ($request->filled('stock_filter')) {
            if ($request->stock_filter === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_filter === 'below_min') {
                $query->whereColumn('stock', '<=', 'stock_lower_limit');
            } elseif ($request->stock_filter === 'high') {
                $query->whereColumn('stock', '>=', 'stock_upper_limit');
            }
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

    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $oldStock = $product->stock;
        $product->stock = $validated['new_stock'];
        $product->save();

        StockAdjustment::create([
            'product_id' => $product->id,
            'registered_by_user_id' => auth()->id(),
            'quantity_changed' => $validated['new_stock'] - $oldStock,
            'custom' => json_encode([
                'reason' => $validated['reason'] ?? null,
                'old_stock' => $oldStock,
                'new_stock' => $validated['new_stock'],
            ]),
        ]);

        return back()->with('success', 'Stock adjusted and adjustment logged.');
    }
}
