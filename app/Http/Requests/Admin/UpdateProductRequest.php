<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $product = $this->route('product');

        $this->merge([
            'slug' => $this->slug ?: Str::slug((string) $this->name),
            'product_id' => $product?->id,
            'is_active' => $this->boolean('is_active'),
            'featured' => $this->boolean('featured'),
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->input('product_id'))],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->input('product_id'))],
            'brand' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'stock' => ['required', 'integer', 'min:0'],
            'featured_image' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['required', 'boolean'],
            'featured' => ['required', 'boolean'],
        ];
    }
}
