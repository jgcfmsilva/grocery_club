<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $this->email)->first();
                    if ($user && Hash::check($value, $user->password)) {
                        $fail('The new password cannot be equal to the current.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'The password is mandatory.',
            'password.confirmed' => 'The passwords do not match.',
            'password.min' => 'The password needs to be at least 8 characters.',
        ];
    }
}
