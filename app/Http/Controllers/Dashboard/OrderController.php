<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\CreditType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Order\CancelOrderRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with('member');

        // Se for employee, só mostra pending
        if (isEmployee()) {
            $query->where('status', \App\Enums\OrderStatus::PENDING->value);
        } else {
            if (request()->filled('status')) {
                $query->where('status', request('status'));
            }
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

        if (!$order->pdf_receipt) {
            return redirect()->back()->with('error', 'Invoice not found.');
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
        $order->load('items.product', 'member');
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

            $order->generateReceipt();

            try {
                $user = $order->member;
                if ($user && $user->email && $order->pdf_receipt) {
                    Mail::to($user->email)
                        ->send(new OrderCompletedMail($order));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send completed order email: ' . $e->getMessage());
            }

            DB::commit();

            flash()->success('Order completed successfully.');
            return redirect()->route('dashboard.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('An error occurred while completing the order.');
            return redirect()->route('dashboard.orders.confirm', $order->id);
        }
    }

    public function cancel(CancelOrderRequest $request, Order $order)
    {
        $validated = $request->validated();

        if (!$order->isPending()) {
            flash()->error('Only pending orders can be canceled.');
            return redirect()->route('dashboard.orders.index');
        }

        DB::beginTransaction();

        try {

            $order->update([
                'status' => OrderStatus::CANCELED,
                'cancel_reason' => $validated['reason']
            ]);

            $card = $order->member->card;

            if ($card) {
                $card->balance += $order->total;
                $card->save();

                $card->operations()->create([
                    'type' => TransactionType::Credit,
                    'value' => $order->total,
                    'date' => now()->toDateString(),
                    'credit_type' => CreditType::OrderCancellation->value,
                    'order_id' => $order->id,
                ]);
            }

            DB::commit();

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success("Order has been canceled successfully. The customer refund will be processed shortly.");

            return redirect()->route('dashboard.orders.index');
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("There was an error canceling the order. Please try again.");

            return redirect()->route('dashboard.orders.index');
        }
    }
}
