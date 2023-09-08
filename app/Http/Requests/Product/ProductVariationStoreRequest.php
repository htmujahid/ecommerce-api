<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariationStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'type' => ['required'],
            'description' => ['nullable', 'max:255'],
            'price' => ['required', 'numeric', 'min:1'],
            'sku' => ['nullable', 'max:255'],
            'order' => ['nullable', 'numeric', 'min:1'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ];
    }
}
