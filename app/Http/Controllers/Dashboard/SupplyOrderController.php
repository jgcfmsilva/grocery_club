<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Dashboard\SupplyOrder\CreateSupplyOrderRequest;

class SupplyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplyOrders = SupplyOrder::with('product')->orderBy('created_at', 'desc')->paginate(20);
        return view('pages.dashboard.inventory.supply-orders.index', compact('supplyOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Product::with('category')->orderBy('name')->get();

        $auto = $request->get('auto');
        $autoProducts = [];
        if ($auto) {
            $autoProducts = $products->filter(function($product) {
                return $product->stock < $product->stock_lower_limit;
            })->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'current_stock' => $product->stock,
                    'stock_upper_limit' => $product->stock_upper_limit,
                    'to_order' => max(0, $product->stock_upper_limit - $product->stock),
                ];
            });
        }

        return view('pages.dashboard.inventory.supply-orders.create', compact('products', 'autoProducts', 'auto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSupplyOrderRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            foreach ($validated['products'] as $item) {
                SupplyOrder::create([
                    'product_id' => $item['id'],
                    'registered_by_user_id' => auth()->id(),
                    'status' => 'requested',
                    'quantity' => $item['quantity'],
                    'custom' => null,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.inventory.supply-orders.index')->with('success', 'Supply order(s) created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Error creating supply order: ' . $e->getMessage());
            return back()->with('error', 'Error creating supply order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplyOrder $supplyOrder)
    {
        $supplyOrder->load('product');
        return view('pages.dashboard.inventory.supply-orders.show', compact('supplyOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplyOrder $supplyOrder)
    {
        $supplyOrder->load('product');
        $products = Product::with('category')->orderBy('name')->get();
        return view('pages.dashboard.inventory.supply-orders.edit', compact('supplyOrder', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplyOrder $supplyOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,canceled,requested',
        ]);

        $supplyOrder->status = $validated['status'];
        $supplyOrder->save();

        return redirect()->route('dashboard.inventory.supply-orders.index')->with('success', 'Supply order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplyOrder $supplyOrder)
    {
        $supplyOrder->delete();
        return redirect()->route('dashboard.inventory.supply-orders.index')->with('success', 'Supply order deleted.');
    }

    /**
     * Complete the specified supply order and update stock.
     */
    public function complete(SupplyOrder $supplyOrder)
    {
        DB::beginTransaction();
        try {
            $product = $supplyOrder->product;
            $oldStock = $product->stock;
            $product->stock += $supplyOrder->quantity;
            $product->save();

            $supplyOrder->status = 'completed';
            $supplyOrder->save();

            // Cria o stock adjustment apenas ao completar
            StockAdjustment::create([
                'product_id' => $product->id,
                'registered_by_user_id' => auth()->id(),
                'quantity_changed' => $supplyOrder->quantity,
                'custom' => json_encode([
                    'type' => 'supply_order_completed',
                    'supply_order_id' => $supplyOrder->id,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->stock,
                ]),
            ]);

            DB::commit();
            return redirect()->route('dashboard.inventory.supply-orders.index')->with('success', 'Supply order completed and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error completing supply order: ' . $e->getMessage());
        }
    }

    /**
     * Adjust the stock of a product manually.
     */
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
            'custom' => $validated['reason'] ?? null,
            'custom' => json_encode([
                    'type' => 'manual_adjustment',
                    'old_stock' => $oldStock,
                    'new_stock' => $product->stock,
                ]),
        ]);

        return back()->with('success', 'Stock adjusted and adjustment logged.');
    }
}
