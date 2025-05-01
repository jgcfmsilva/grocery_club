<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\CreditType;
use App\Enums\TransactionType;
use App\Enums\DebitType;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Order\CancelOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
    * Show view of all orders
    */
    public function index()
    {
        $orders = Order::where('member_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('pages.my-account.orders.index', compact('orders'));
    }

    /**
     * Show view of a specific order
    */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product');

        return view('pages.my-account.orders.show', compact('order'));
    }

    /**
     * Download receipt of specific order
    */
    public function downloadReceipt(Order $order)
    {
        $this->authorize('downloadReceipt', $order);
        $order->load(['items.product', UserType::Member->value]);

        $receiptsDir = storage_path('app/private/receipts');

        if (!file_exists($receiptsDir) && !mkdir($receiptsDir, 0755, true) && !is_dir($receiptsDir)) {
            throw new \RuntimeException('Cannot create receipts directory');
        }

        $filename = $order->pdf_receipt;
        $filePath = "{$receiptsDir}/{$filename}";

        if ($order->pdf_receipt && file_exists($filePath)) {
            $pdfModifiedTime = filemtime($filePath);
            $orderModifiedTime = $order->updated_at->timestamp;

            if ($pdfModifiedTime >= $orderModifiedTime) {
                return response()->download($filePath, $filename);
            }
        }

        return $this->generateReceipt($order);
    }

    /**
     * Cancel the specified order.
    */
    public function cancel(CancelOrderRequest $request, Order $order)
    {
        $this->authorize('cancel', $order);

        $validated = $request->validated();

        $cancelReason = authUser()->type === UserType::Member ? null : $validated['reason'];

        DB::beginTransaction();

        try {
            
            $order->update([
                'status' => OrderStatus::CANCELED,
                'cancel_reason' => $cancelReason
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
                ->success("Order has been canceled successfully. Your refund will be processed shortly.");

            return redirect()->route('my-account.orders.show', $order);
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("There was an error canceling the order. Please try again.");

            return redirect()->route('my-account.orders.show', $order);
        }
    }

    /**
     * Reorder items from a previous order.
    */
    public function reorder(Order $order)
    {
        $this->authorize('view', $order);

        $user = authUser();

        DB::beginTransaction();

        try {
            $newOrder = $user->orders()->create([
                'status' => OrderStatus::PENDING,
                'date' => now(),
                'total_items' => 0,
                'shipping_cost' => 0,
                'total' => 0,
                'delivery_address' => $order->delivery_address,
                'nif' => $order->nif
            ]);

            $card = $user->card;

            if ($card) {
                $card->operations()->create([
                    'type' => TransactionType::Debit,
                    'value' => $order->total,
                    'date' => now()->toDateString(),
                    'credit_type' => DebitType::Order,
                    'order_id' => $newOrder->id,
                ]);
            }

            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $unitPrice = $product->price;
                    $discount = 0;


                    if ($product->discount_min_qty && $item->quantity >= $product->discount_min_qty) {
                        $discount = $product->discount;
                    }

                    $newOrder->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                        'discount' => $discount,
                        'subtotal' => ($unitPrice - $discount) * $item->quantity
                    ]);
                }
            }

            $this->updateOrderTotals($newOrder);

            DB::commit();

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success('New order created from your previous order #' . $order->id);

            return redirect()->route('my-account.orders.show', $newOrder);
        } catch (\Exception $e) {
            DB::rollBack();
    
            report($e);
    
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('There was an error creating the new order. Please try again.');
    
            return redirect()->route('my-account.orders.show', $order);
        }
    }

    /**
     * Generate a PDF receipt for the order.
    */
    protected function generateReceipt(Order $order, bool $download = true)
    {
        try {
            $order->load(['items.product', 'member.card']);

            $pdf = Pdf::loadView('pdf.receipt', [
                'order' => $order,
                'items' => $order->items
            ])->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

            $filename = "receipt_{$order->id}_" . time() . '.pdf';
            $filePath = storage_path("app/private/receipts/{$filename}");

            $pdf->save($filePath);

            $order->update(['pdf_receipt' => $filename]);

            return $download ? $pdf->download($filename) : $filePath;

        } catch (\Exception $e) {
            Log::error("Failed to generate receipt for order {$order->id}: " . $e->getMessage());

            if ($download) {
                return redirect()->back()
                    ->with('error', 'Failed to generate receipt. Please try again later.');
            }

            throw $e;
        }
    }

    /**
     * Update order totals based on items and shipping costs.
    */
    protected function updateOrderTotals(Order $order)
    {
        $order->load('items');

        $totalItems = $order->items->sum('subtotal');
        $shippingCost = $this->calculateShippingCost($totalItems);
        $total = $totalItems + $shippingCost;

        $order->update([
            'total_items' => $totalItems,
            'shipping_cost' => $shippingCost,
            'total' => $total
        ]);
    }
}
