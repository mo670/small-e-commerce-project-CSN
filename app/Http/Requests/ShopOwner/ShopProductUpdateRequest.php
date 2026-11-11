<?php

namespace App\Http\Requests\ShopOwner;

use Illuminate\Foundation\Http\FormRequest;

class ShopProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isShopOwner() || $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:0'],
            'details' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
