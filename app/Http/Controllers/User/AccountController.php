<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function show()
    {
        return view('pages.my-account.index');
    }

    public function update(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'gender' => 'required|in:M,F',
            'nif' => 'nullable|digits:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Validação para o campo 'default_payment_reference', dependendo do tipo de pagamento
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

        // Verifica se a imagem é a mesma que a atual, se não for dá upload se for mantém o caminho atual
        if ($request->hasFile('photo')) {
            $newPhoto = $request->file('photo');
            $currentPhotoPath = Auth::user()->photo;
            $currentFullPath = storage_path('app/public/' . $currentPhotoPath);
        
            if (!file_exists($currentFullPath) || md5_file($newPhoto->getRealPath()) !== md5_file($currentFullPath)) {
                if ($currentPhotoPath && Storage::disk('public')->exists($currentPhotoPath)) {
                    Storage::disk('public')->delete($currentPhotoPath);
                }
                
                $photoPath = $newPhoto->store('users', 'public');
            } else {
                $photoPath = $currentPhotoPath;
            }
        } else {
            $photoPath = Auth::user()->photo;
        }

        // Atualização do usuário
        $user = Auth::user();
        $user->update([
            'name' => $validator->validated()['name'],
            'email' => $validator->validated()['email'],
            'gender' => $validator->validated()['gender'],
            'nif' => $validator->validated()['nif'] ?? $user->nif,
            'default_delivery_address' => $validator->validated()['default_delivery_address'] ?? $user->default_delivery_address,
            'default_payment_type' => $validator->validated()['default_payment_type'] ?? $user->default_payment_type,
            'default_payment_reference' => $validator->validated()['default_payment_reference'] ?? $user->default_payment_reference,
            'photo' => $photoPath,
        ]);

        return redirect()->route('my-account.index')->with('success', 'Profile updated successfully!');
    }

    public function showChangePassword()
    {
        return view('pages.my-account.change-password');
    }

    public function updatePassword(Request $request)
    {
        // Vai ser implementado mais tarde
    }
}
