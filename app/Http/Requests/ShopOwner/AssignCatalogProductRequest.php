<?php

namespace App\Http\Requests\ShopOwner;

use Illuminate\Foundation\Http\FormRequest;

class AssignCatalogProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isShopOwner() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'details' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
