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
            "brand" => 'prohibits:item,type|exists:brands',
            "type" => 'prohibits:brand,item|exists:types',
            "item" => 'prohibits:brand,type|exists:items',
        ];
    }
}
