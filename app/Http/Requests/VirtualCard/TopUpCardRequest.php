<?php

namespace App\Http\Requests\VirtualCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TopUpCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'default_payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'default_payment_reference' => 'required',
            'value' => 'required|numeric|min:0.01',
            'payment_cvc' => 'required_if:default_payment_type,Visa|nullable|digits:3',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $type = $this->input('default_payment_type');
            $reference = $this->input('default_payment_reference');
            $cvc = $this->input('payment_cvc');

            if ($type === 'Visa') {
                if (!preg_match('/^[1-9][0-9]{14}[0-13-9]$/', $reference)) {
                    $validator->errors()->add('default_payment_reference', 'The Visa card number must have 16 digits, not start with 0, and not end with 2.');
                }

                if (!preg_match('/^[1-9][0-9]{1}[0-13-9]$/', $cvc)) {
                    $validator->errors()->add('payment_cvc', 'The CVC must have 3 digits, not start with 0, and not end with 2.');
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