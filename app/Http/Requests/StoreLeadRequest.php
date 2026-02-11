<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'country_id' => 'required|exists:countries,id',
            'email' => 'required|email|max:255',
        ];
    }
}
