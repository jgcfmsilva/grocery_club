<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes()
    {
        return [
            'current_password' => 'current password',
            'password' => 'new password',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $messages = implode(' ', $validator->errors()->all());
        flash()
            ->option('position', 'bottom-right')
            ->option('timeout', 3000)
            ->error($messages);

        throw new HttpResponseException(
            redirect()->back()->withInput()
        );
    }
}
