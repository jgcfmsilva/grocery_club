<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1',
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
