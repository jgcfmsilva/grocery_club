<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateAccountRequest extends FormRequest
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
            'default_payment_reference' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $type = $this->input('default_payment_type');

                    if ($type === 'Visa' && !preg_match('/^[1-9][0-9]{14}[0-13-9]$/', $value)) {
                        $fail('Invalid Visa reference format.');
                    }

                    if ($type === 'PayPal' && !preg_match('/^[\w\.-]+@[\w\.-]+\.(pt|com)$/i', $value)) {
                        $fail('Invalid PayPal email format.');
                    }

                    if ($type === 'MB WAY' && !preg_match('/^9[0-9]{7}[0-13-9]$/', $value)) {
                        $fail('Invalid MB WAY number format.');
                    }
                },
            ],
        ];
    }
}
