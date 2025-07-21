<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;    

class EmailVerificationController extends Controller
{   

    /**
     * Show the sent confirmation email page
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'The email is already validated!');
        }

        return view('pages.auth.email-confirmation');
    }

    public function finish()
    {
        return view('pages.auth.email-validated');
    }

    /**
     * Mark the user's email as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'The email is already validated!');
        }

        if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
            $user->markEmailAsVerified();

            session(['email_validated' => true]);

            return redirect()->route('email.validated');
        }

        return redirect()->route('home')->with('error', 'The verification link is invalid or expired!');
    }


    /**
     * Resend the verification email to user
     *
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('info', 'Your email is already validated!');
        }

        $user->sendEmailVerificationNotification();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success("A new verification email has been sent to your email address. Please check your inbox.");

        return back()->with('resent', true);
    }
}
