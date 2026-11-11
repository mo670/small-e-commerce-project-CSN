<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ShopOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $userId = $this->route('shop_owner')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => $userId ? ['nullable', Password::defaults()] : ['required', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'shop_id' => ['nullable', 'integer', 'exists:shops,id'],
        ];
    }
}
