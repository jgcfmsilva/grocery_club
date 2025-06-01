<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\User\UpdateAccountRequest;


class AccountController extends Controller
{
    public function show()
    {
        return view('pages.my-account.index');
    }

    public function update(UpdateAccountRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            $newPhoto = $request->file('photo');
            $currentPhotoPath = authUser()->photo;
            $currentFullPath = storage_path('app/public/' . $currentPhotoPath);

            if (!file_exists($currentFullPath) || md5_file($newPhoto->getRealPath()) !== md5_file($currentFullPath)) {
                if ($currentPhotoPath && \Storage::disk('public')->exists($currentPhotoPath)) {
                    \Storage::disk('public')->delete($currentPhotoPath);
                }
                $photoPath = $newPhoto->store('users', 'public');
            } else {
                $photoPath = $currentPhotoPath;
            }
        } else {
            $photoPath = authUser()->photo;
        }

        $user = authUser();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'nif' => $validated['nif'] ?? null,
            'default_delivery_address' => $validated['default_delivery_address'] ?? null,
            'default_payment_type' => $validated['default_payment_type'] ?? null,
            'default_payment_reference' => $validated['default_payment_reference'] ?? null,
            'photo' => $photoPath ? basename($photoPath) : null,
        ]);

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->success("Profile updated successfully!");

        return redirect()->route('my-account.index');
    }

    public function showChangePassword()
    {
        return view('pages.my-account.change-password');
    }
}
