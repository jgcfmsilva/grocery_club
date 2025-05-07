<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('member');

        // Apply filters
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

        // Apply sorting
        if (request()->has('sort') && request()->has('direction')) {
            $sortableColumns = ['id', 'date', 'total'];
            $sort = request('sort');
            $direction = request('direction') === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, $sortableColumns)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            // Default: most recent orders first
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
}
