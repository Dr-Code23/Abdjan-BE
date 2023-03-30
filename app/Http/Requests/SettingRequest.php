<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SettingRequest extends FormRequest
{
    use HttpResponse;

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $inputs = $this->all();

        if(isset($inputs['name']) && is_string($inputs['name'])){
            $inputs['name'] = json_decode($inputs['name'] , true);
        }

        if(isset($inputs['phones']) && is_string($inputs['phones'])){
            $inputs['phones'] = json_decode($inputs['phones'] , true);
        }

        if(isset($inputs['social_links']) && is_string($inputs['social_links'])){
            $inputs['social_links'] = json_decode($inputs['social_links'] , true);
        }

        if(isset($inputs['logo']) && !$inputs['logo']) {
            unset($inputs['logo']);
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
            'logo' => [
                'sometimes',
                'file',
                'mimes:jpg,png,jpeg',
                'max:10000'
            ],
            'phones' => ['sometimes' , 'array'],
            'phones.*' => ['sometimes'],
            'social_links' => ['sometimes' , 'array'],
            'social_links.*' => ['sometimes' , 'active_url']
        ];

        addTranslationRules($rules);

        return $rules;
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
