<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operation;
use App\Services\Payment;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function index()
    {
        $card = authUser()->card;
        return view('pages.my-account.virtual-card.index', compact('card'));
    }

    public function topUpCard(Request $request)
    {
        var_dump($request);
        $request->validate([
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_reference' => 'required',
            'value' => 'required|numeric|min:1',
        ]);

        $user = authUser();
        $card = $user->card;
        $success = false;

        // Simular pagamento
        if ($request->payment_type === 'Visa') {
            $success = Payment::payWithVisa($request->payment_reference, '123');
        } elseif ($request->payment_type === 'PayPal') {
            $success = Payment::payWithPayPal($request->payment_reference);
        } elseif ($request->payment_type === 'MB WAY') {
            $success = Payment::payWithMBway($request->payment_reference);
        }

        if (!$success) {
            flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->error("Payment failed. Please check the reference and try again!");

            return back();
        }

        DB::transaction(function () use ($card, $request) {
            $card->increment('balance', $request->value);

            Operation::create([
                'card_id' => $card->id,
                'type' => 'credit',
                'value' => $request->value,
                'date' => now()->toDateString(),
                'credit_type' => 'payment',
                'payment_type' => $request->payment_type,
                'payment_reference' => $request->payment_reference,
            ]);
        });

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success("Funds successfully added to the card.");

        return back();
    }
}
