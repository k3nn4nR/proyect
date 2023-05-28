<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeStoreRequest extends FormRequest
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
            'code' => 'required|unique:codes',
            'brand' => 'required_without_all:item,type,currency|prohibits:item,type,currency|exists:brands',
            'type' => 'required_without_all:brand,item,currency|prohibits:brand,item,currency|exists:types',
            'item' => 'required_without_all:brand,type,currency|prohibits:brand,type,currency|exists:items',
            'currency' => 'required_without_all:brand,type,items|prohibits:brand,type,items|exists:currencies',
        ];
    }
}
