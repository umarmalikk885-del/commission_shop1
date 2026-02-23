<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && (
            $user->hasRole('Mandi Owner') ||
            $user->hasRole('Product Owner') ||
            $user->hasRole('Super Admin')
        );
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date'],
            'product_owner_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
