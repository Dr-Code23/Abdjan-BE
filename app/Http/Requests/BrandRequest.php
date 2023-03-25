<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
        if($this->method() != 'POST'){
            if(!$this->file('img')){
                unset($data['img']);
            }
        }
        try{

            if(is_string($data['name'])) {
                $data['name'] = json_decode($data['name'], true);
            }
            if(isset($data['description']) && is_string($data['description'])){
                $data['description'] = json_decode($data['description'] , true);
            }

        }catch(\Exception $e){}

        $this->replace($data);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [

        ];
        addTranslationRules($rules);

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}

