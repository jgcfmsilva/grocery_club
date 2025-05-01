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
        return view('auth.register');
    }

    /**
     * Processa a criação do utilizador
     */
    public function register(RegisterUserRequest $request)
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'nif' => $request->nif,
            'default_delivery_address' => $request->default_delivery_address,
            'default_payment_type' => $request->default_payment_type,
            'default_payment_reference' => $request->default_payment_reference,
            'photo' => $photoPath,
            'type' => UserType::Member->value,
        ]);

        do {
            $cardNumber = random_int(100000, 999999);
        } while (Card::where('card_number', $cardNumber)->exists());

        Card::create([
            'id' => $user->id,
            'card_number' => $cardNumber,
            'balance' => 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::MYACCOUNT);
    }
}
