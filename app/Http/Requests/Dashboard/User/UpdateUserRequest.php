<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $editingUser = $this->route('user');
        $originalEmail = is_object($editingUser) ? $editingUser->email : null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($originalEmail) {
                    if ($originalEmail !== null && $value !== $originalEmail) {
                        $fail('The email cannot be changed.');
                    }
                },
            ],
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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('default_payment_type');
            $reference = $this->input('default_payment_reference');

            if ($type === 'Visa') {
                if (!preg_match('/^[1-9][0-9]{14}[0-13-9]$/', $reference)) {
                    $validator->errors()->add('default_payment_reference', 'The Visa card number must have 16 digits, not start with 0, and not end with 2.');
                }
            }

            if ($type === 'PayPal') {
                if (!filter_var($reference, FILTER_VALIDATE_EMAIL) || 
                    !(str_ends_with($reference, '.pt') || str_ends_with($reference, '.com'))) {
                    $validator->errors()->add('default_payment_reference', 'The PayPal email must be valid and end with .pt or .com.');
                }
            }

            if ($type === 'MB WAY') {
                if (!preg_match('/^9[0-9]{7}[0-13-9]$/', $reference)) {
                    $validator->errors()->add('default_payment_reference', 'The MB WAY number must start with 9, have 9 digits, and not end with 2.');
                }
            }
        });
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
