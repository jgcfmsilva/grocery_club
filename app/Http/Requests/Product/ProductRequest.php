<?php

namespace App\Http\Requests\Product;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'discount_min_qty' => 'nullable|integer|min:1',
            'discount' => 'nullable|numeric|min:0|lte:price|regex:/^\d+(\.\d{1,2})?$/',
            'stock_lower_limit' => 'required|integer|min:0',
            'stock_upper_limit' => 'required|integer|gte:stock_lower_limit',
            'custom' => 'nullable|json',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'price.regex' => 'The price must have at most two decimal places.',
            'stock.required' => 'The stock is required.',
            'stock.integer' => 'The stock must be an integer.',
            'stock.min' => 'The stock must be at least 0.',
            'description.required' => 'The description is required.',
            'photo.image' => 'The photo must be an image.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg.',
            'photo.max' => 'The photo must not exceed 2MB.',
            'discount_min_qty.integer' => 'The discount minimum quantity must be an integer.',
            'discount_min_qty.min' => 'The discount minimum quantity must be at least 1.',
            'discount.numeric' => 'The discount must be a valid number.',
            'discount.min' => 'The discount must be at least 0.',
            'discount.lte' => 'The discount cannot be greater than the price.',
            'discount.regex' => 'The discount must have at most two decimal places.',
            'stock_lower_limit.required' => 'The stock lower limit is required.',
            'stock_lower_limit.integer' => 'The stock lower limit must be an integer.',
            'stock_lower_limit.min' => 'The stock lower limit must be at least 0.',
            'stock_upper_limit.required' => 'The stock upper limit is required.',
            'stock_upper_limit.integer' => 'The stock upper limit must be an integer.',
            'stock_upper_limit.gte' => 'The stock upper limit must be greater than or equal to the stock lower limit.',
            'custom.json' => 'The custom data must be a valid JSON string.',
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
