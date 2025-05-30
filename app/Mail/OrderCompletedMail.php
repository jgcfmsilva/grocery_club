<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('items.product', 'member');
    }

    public function build()
    {
        $pdfPath = storage_path('app/private/receipts/' . $this->order->pdf_receipt);

        return $this->subject('Your Order #' . $this->order->id . ' is Completed')
            ->view('emails.orders.completed')
            ->attach($pdfPath, [
                'as' => 'receipt_' . $this->order->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
