<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LoginUserController extends Controller
{
    // shows the view
    public function show()
    {
        if(!auth::check()) {
            return view('auth.login');
        } else {
            return redirect("my-account");
        }

    }

    // validates the data and if correct logins
    public function login(Request $request)
    {
        // apenas pode aceder caso nao esteja logado
        if (!auth::check()) {
            // valida os dados
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',

            ]);

            // os dados sao invalidos
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Tentativa de autenticação
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect("my-account");
            }

            // Se a autenticação falhar
            return back()->withErrors([
                'email' => 'Invalid email or password. Please try again.',
            ])->onlyInput('email');
        }
    }

    // logouts the user
    public function logout(Request $request)
    {

        // protects the endpoint
        if(auth::check() &&  $request -> ismethod('POST')){
            // shuts down the session
            Auth::logout();

            // Invalidates the session
            $request->session()->invalidate();

            // regenerates the csrf token
            $request->session()->regenerateToken();

            // redirects
            return redirect('/');
        } else {
            // checks if the user is logged in
            if(auth::check()):
                // redirects
                return redirect('my-account');
            endif;
        }

    }


}
