<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Card;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'gender' => 'required|in:M,F',
            'nif' => 'nullable|digits:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'photo' => 'nullable|image|max:2048',
        ]);

        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^[1-9][0-9]{14}[0-13-9]$/', // 16 dígitos, não começa com 0, não termina com 2
        ], function ($input) {
            return $input->default_payment_type === 'Visa';
        });
        
        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^[\w\.-]+@[\w\.-]+\.(pt|com)$/i' // Email válido, termina em .pt ou .com
        ], function ($input) {
            return $input->default_payment_type === 'PayPal';
        });
        
        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^9[0-9]{7}[0-13-9]$/', // Começa com 9, 9 dígitos, não termina com 2
        ], function ($input) {
            return $input->default_payment_type === 'MB WAY';
        });

        // Se ocorrer erro volta para trás mostrando o erro e colocando os dados originais de volta
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Upload da foto de perfil (opcional)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }
        
        // Criação do usuário
        $user = User::create([
            'name' => $validator->validated()['name'],
            'email' => $validator->validated()['email'],
            'password' => Hash::make($validator->validated()['password']), // Password with hash
            'gender' => $validator->validated()['gender'],
            'nif' => $validator->validated()['nif'] ?? null,
            'default_delivery_address' => $validator->validated()['default_delivery_address'] ?? null,
            'default_payment_type' => $validator->validated()['default_payment_type'] ?? null,
            'default_payment_reference' => $validator->validated()['default_payment_reference'] ?? null,
            'photo' => $photoPath,
            'type' => 'member',
        ]);

        do {
            $cardNumber = random_int(100000, 999999);
        } while (Card::where('card_number', $cardNumber)->exists());

        // Criar cartão virtual
        Card::create([
            'id' => $user->id,
            'card_number' => $cardNumber,
            'balance' => 0,
        ]);
        

        // Disparar evento de verificação de email
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::MYACCOUNT);
    }
}
