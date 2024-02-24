<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'   => 'required|string',
            'amount' => 'nullable|numeric',
        ];
    }

    /**
     * Get the validation rules messages.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'name.required'   => __('customer.name_required'),
            'name.string'     => __('customer.name_string'),
            'amount.numeric'  => __('customer.amount_numeric')
        ];
    }
}
