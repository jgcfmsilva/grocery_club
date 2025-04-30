<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'gender' => 'required|in:M,F',
            'nif' => 'nullable|digits:9',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^[1-9][0-9]{14}[0-13-9]$/',
        ], function ($input) {
            return $input->default_payment_type === 'Visa';
        });

        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^[\w\.-]+@[\w\.-]+\.(pt|com)$/i',
        ], function ($input) {
            return $input->default_payment_type === 'PayPal';
        });

        $validator->sometimes('default_payment_reference', [
            'required',
            'regex:/^9[0-9]{7}[0-13-9]$/',
        ], function ($input) {
            return $input->default_payment_type === 'MB WAY';
        });
    }
}
