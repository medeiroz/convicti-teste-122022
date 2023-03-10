<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam('location.latitude', 'Latitude Coordinate', required: true, example: -30.1141)]
#[BodyParam('location.longitude', 'Longitude Coordinate', required: true, example: -51.3281)]
#[BodyParam('price', 'Price', required: true, example: 10.50)]
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
            'location.latitude' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
            'location.longitude' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
        ];
    }
}
