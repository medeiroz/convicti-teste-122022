<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => ['required'],
            'price' => [
                'required',
                'regex:/^\d*(\.\d{1,2})?$/',
                'gt:0'
            ],
            'sold_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
