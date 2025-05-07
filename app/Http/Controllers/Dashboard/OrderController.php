<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('member');

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('date')) {
            $query->whereDate('date', request('date'));
        }

        if (request()->filled('member')) {
            $query->whereHas('member', function ($q) {
                $q->where('name', 'like', '%' . request('member') . '%');
            });
        }

        if (request()->has('sort') && request()->has('direction')) {
            $sortableColumns = ['id', 'date', 'total'];
            $sort = request('sort');
            $direction = request('direction') === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, $sortableColumns)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('date', 'desc');
        }

        $orders = $query->paginate(10);

        return view('pages.dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'member');
        return view('pages.dashboard.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();

            flash()->success('Order deleted successfully.');
            return redirect()->route('dashboard.orders.index');
        } catch (\Exception $e) {
            flash()->error('An error occurred while deleting the order.');
            return redirect()->route('dashboard.orders.index');
        }
    }

    public function invoice(Order $order)
    {
        if (!$order->isCompleted()) {
            abort(403, 'Invoice is only available for completed orders.');
        }

        $filePath = storage_path("app/private/receipts/{$order->pdf_receipt}");

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $order->pdf_receipt . '"'
        ]);
    }
    
    public function confirm(Order $order)
    {
        $order->load('items.product', 'member');
        $itemsWithStock = [];
        $can_complete = true;

        foreach ($order->items as $item) {
            $has_stock = $item->product->stock >= $item->quantity;
            $itemsWithStock[] = [
                'item' => $item,
                'has_stock' => $has_stock,
            ];
            if (!$has_stock) {
                $can_complete = false;
            }
        }

        return view('pages.dashboard.orders.confirm', compact('order', 'itemsWithStock', 'can_complete'));
    }

    public function complete(Order $order)
    {
        $order->load('items.product');
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    flash()->error('Cannot complete the order. Insufficient stock for product: ' . $item->product->name);
                    return redirect()->route('dashboard.orders.confirm', $order->id);
                }
            }

            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }
            
            $order->status = OrderStatus::COMPLETED;
            $order->save();

            DB::commit();
            flash()->success('Order completed successfully.');
            return redirect()->route('dashboard.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('An error occurred while completing the order.');
            return redirect()->route('dashboard.orders.confirm', $order->id);
        }
    }
}
