<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required' ,
                'image',
                'mimes:jpg,png,jpeg',
                'max:10000'
            ]
        ];
    }
}
