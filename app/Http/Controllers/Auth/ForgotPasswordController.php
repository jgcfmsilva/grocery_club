<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function show(Request $request)
    {
        // validates if it was via post and checks if the user is not logged in
        if(!Auth::check()):

            // checks if the email is not null
            if($request->only('email') == null):
                return view('auth.forgot-password', [
                    'email' => ''
                ]);
            else:
                return view('auth.forgot-password', [
                    'email' => $request->only('email')['email']
                ]);
            endif;
        else:
            return redirect('/');
        endif;
    }

    public function sendEmailVerification(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
