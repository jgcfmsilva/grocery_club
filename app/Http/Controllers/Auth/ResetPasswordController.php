<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    // shows the view
    public function show(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // handles the password reset
    public function reset(Request $request)
    {
        // validates the data
        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:8',

                // checks if the password is not equal to the current
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::where('email', $request->email)->first();
                    if ($user && Hash::check($value, $user->password)) {
                        $fail('The new password cannot be equal to the current.');
                    }
                },
            ],
        ], [
            'password.required' => 'The password is mandatory.',
            'password.confirmed' => 'The password do not match.',
            'password.min' => 'The password needs to 8 chars long.'
        ]);

        // tries to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        // makes the final verification
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['password' => [__($status)]]);
    }
}
