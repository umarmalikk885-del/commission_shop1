<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && (
            $user->hasRole('Mandi Owner') ||
            $user->hasRole('Purchaser') ||
            $user->hasRole('Super Admin')
        );
    }

    public function rules(): array
    {
        return [
            'quantity_delta' => ['required', 'numeric', 'not_in:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
