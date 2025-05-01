<?php

namespace App\Http\Controllers\User;

use App\Enums\CreditType;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\CardOperation;
use App\Services\Payment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VirtualCard\TopUpCardRequest;

class CardController extends Controller
{
    public function index()
    {
        $card = authUser()->card;
        return view('pages.my-account.virtual-card.index', compact('card'));
    }

    public function topUpCard(TopUpCardRequest $request)
    {
        $user = authUser();
        $card = $user->card;

        if (!$card) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("Card not found.");
        
            return back();
        }

        $success = false;

        // Simulate payment
        if ($request->default_payment_type === PaymentMethod::Visa->value) {
            $success = Payment::payWithVisa($request->default_payment_reference, $request->payment_cvc);
        } elseif ($request->default_payment_type === PaymentMethod::PayPal->value) {
            $success = Payment::payWithPayPal($request->default_payment_reference);
        } elseif ($request->default_payment_type === PaymentMethod::MBWay->value) {
            $success = Payment::payWithMBway($request->default_payment_reference);
        }

        if (!$success) {
            flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->error("Payment failed. Please check the reference and try again!");

            return back();
        }

        try {
            DB::transaction(function () use ($card, $request) {
                $card->increment('balance', $request->value);

                $card->operations()->create([
                    'card_id' => $card->id,
                    'type' => TransactionType::Credit,
                    'value' => $request->value,
                    'date' => now()->toDateString(),
                    'credit_type' => CreditType::Payment,
                    'payment_type' => $request->default_payment_type,
                    'payment_reference' => $request->default_payment_reference,
                ]);
            });

            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->success("Funds successfully added to the card.");
        } catch (\Throwable $e) {
            report($e);
    
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("An error occurred while processing the transaction. Please try again later.");
        }

        return back();
    }
}
