<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', Password::defaults()],
            'gender' => 'required|in:M,F',
            'nif' => 'nullable|digits:9',
            'type' => 'required|in:pending_member,member,employee,board',
            'blocked' => 'required|boolean',
            'default_delivery_address' => 'nullable|string|max:255',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'photo' => 'nullable|image|max:5120',
            'default_payment_reference' => [
                'nullable',
                'string'
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $messages = collect($validator->errors()->all())->implode("\n");

        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 6000)
            ->error($messages);

        throw new HttpResponseException(
            redirect()->back()->withInput()
        );
    }
}
