<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
{

    use HttpResponse;

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if(isset($inputs['name']) && is_string($inputs['name'])){
            $inputs['name'] = json_decode($inputs['name'] , true);
        }

        if(isset($inputs['description']) && is_string($inputs['description'])){
            $inputs['description'] = json_decode($inputs['description'] , true);
        }

        if(isset($inputs['image']) && !$inputs['image']) {
            unset($inputs['image']);
        }

        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'image' => [
                'sometimes',
                'file',
                'mimes:jpg,png,jpeg',
                'max:10000'
            ]
        ];

        addTranslationRules($rules, ['name', 'description']);

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
