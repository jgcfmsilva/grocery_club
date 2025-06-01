<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Card;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Exibe o formulário de criação de conta.
     */
    public function show()
    {
        return view('pages.auth.register');
    }

    /**
     * Processa a criação do utilizador
     */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $photoPath = basename($photoPath);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'nif' => $validated['nif'] ?? null,
            'default_delivery_address' => $validated['default_delivery_address'] ?? null,
            'default_payment_type' => $validated['default_payment_type'] ?? null,
            'default_payment_reference' => $validated['default_payment_reference'] ?? null,
            'photo' => $photoPath,
            'type' => UserType::PendingMember->value,
        ]);

        do {
            $cardNumber = random_int(100000, 999999);
        } while (Card::where('card_number', $cardNumber)->exists());

        Card::create([
            'id' => $user->id,
            'card_number' => $cardNumber,
            'balance' => 0,
        ]);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error("Account created successfully, but the verification email could not be sent.\nError: " . $e->getMessage());
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::MYACCOUNT);
    }
}
