<?php

namespace App\Http\Controllers\Membership;

use App\Enums\TransactionType;
use App\Enums\DebitType;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\CardOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils\Constants;
use Throwable;

class MembershipController extends Controller
{
    public function index()
    {
        $user = authUser();

        $this->authorize('view', $user);

        $membershipFee = DB::table('settings')->value('membership_fee') ?? 0;

        return view('pages.my-account.membership.index', compact('membershipFee'));
    }

    public function pay()
    {   
        $user = authUser();

        $this->authorize('payMembership', $user);

        if (empty($user->email_verified_at)) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("You must confirm your email before paying the membership.");
            return back();
        }

        $membershipFee = DB::table('settings')->value('membership_fee') ?? 0;

        $card = $user->card;

        if (!$card) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("Card not found.");
        
            return back();
        }

        if ($card->balance < $membershipFee) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("There are not enough funds on the virtual card.");
        
            return back();
        }

        try {
            DB::transaction(function () use ($membershipFee, $card, $user) {
                $card->decreaseBalance($membershipFee, [
                    'type' => TransactionType::Debit->value,
                    'value' => $membershipFee,
                    'date' => now()->toDateString(),
                    'debit_type' => DebitType::MembershipFee->value,
                ]);

                $user->changeType(UserType::Member);

                flash()
                    ->option('position', 'bottom-right')
                    ->option('timeout', 3000)
                    ->success("Membership paid successfully! Your Account is now activated.");
            });
            
            return redirect()->back();
        } catch (Throwable $e) {
            Log::error('Erro ao processar pagamento: ' . $e->getMessage());
        
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("There was an error paying the membership. Please try again.");

            return redirect()->back();
        }
    }
}
