<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required',
            'customer_id' => 'required',
            'value' => 'required|decimal:2',
            'holder_name' => 'required_if:type,CREDIT_CARD',
            'number' => 'required_if:type,CREDIT_CARD',
            'expiry_month' => 'required_if:type,==,CREDIT_CARD|nullable|numeric|digits:2|min:1|max:12',
            'expiry_year' => 'required_if:type,==,CREDIT_CARD|nullable|numeric|digits:4',
            'ccv' => 'required_if:type,==,CREDIT_CARD|nullable|numeric|digits:3',
        ];
    }
}