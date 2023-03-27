<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BrandRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $data = $this->all();
        if(!preg_match("/.*brands$/",$this->url())){
            if(!$this->file('img')){
                unset($data['img']);
            }
        }

        if(isset($data['name']) && is_string($data['name'])) {
            $data['name'] = json_decode($data['name'], true);
        }
//        if(isset($data['description']) && is_string($data['description'])){
//            $data['description'] = json_decode($data['description'] , true);
//        }

        $this->replace($data);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $isUpdate = preg_match("/.*brands$/",$this->url());

        $rules = [
            'img' => [
                $isUpdate ? 'required': 'sometimes' ,
                'file' ,
                'mimes:png,jpg,jfif' ,
                'max:2000'
            ]
        ];

        addTranslationRules($rules);
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}

