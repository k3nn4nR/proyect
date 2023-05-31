<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'company'   => 'required|exists:companies',
            'currency'  => 'required|exists:currencies',
            'items'   => 'filled',
            'services'=> 'filled',
            'items.*'   => 'required|exists:items,item',
            'services.*'=> 'required|exists:services,service',
            'items_ammount.*' => 'required_with:items',
            'items_price.*'   => 'required_with:items',
            'services_ammount.*'  => 'required_with:services',
            'services_price.*'    => 'required_with:services',
        ];
    }
}
