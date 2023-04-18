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

//        if(isset($inputs['phone'])){
//           $inputs['phone'] = trim($inputs['phone'] , " \ \t\n\r\0\x0B/");
//        }

        if(!$this->hasFile('logo')) {
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
            'phones' => ['required'],
            'facebook' => 'sometimes',
            'instagram' => 'sometimes',
            'youtube' => 'sometimes',
            'whatsapp' => 'sometimes',
            'address' => 'required',
            'email' => 'required'
        ];

//        addTranslationRules($rules);
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
