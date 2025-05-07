<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Support\Facades\Auth;


class LoginUserController extends Controller
{
    // shows the view
    public function show()
    {
        if(!auth::check()) {
            return view('pages.auth.login');
        } else {
            return redirect("my-account");
        }

    }

    // validates the data and if correct logins
    public function login(LoginUserRequest $request)
    {
        if (!Auth::check()) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect("my-account");
            }

            return back()->withErrors([
                'email' => 'Invalid email or password. Please try again.',
            ])->onlyInput('email');
        }
    }

    // logouts the user
    public function logout(Request $request)
    {

        if(auth::check() &&  $request -> ismethod('POST')){
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        } else {
            if(auth::check()):
                return redirect('my-account');
            endif;
        }

    }


}
