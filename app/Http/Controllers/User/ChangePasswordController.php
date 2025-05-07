<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ChangePasswordRequest;

class ChangePasswordController extends Controller
{

    public function index()
    {
        return view('pages.my-account.change-password.index');
    }

    public function update(ChangePasswordRequest $request)
    {
        if (!Hash::check($request->current_password, $request->user()->password)) {
            flash()
                ->option('position', 'bottom-right')
                ->option('timeout', 3000)
                ->error('The current password is incorrect.');
            return back();
        }

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success('Password changed successfully!');
        return back();
    }
}
