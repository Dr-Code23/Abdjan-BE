<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    use HttpResponse;
    private bool $isUpdate = false;

    public function __construct()
    {
        parent::__construct();
        if(!preg_match("/.*parent_categories$/",request()->url())){
            $this->isUpdate = true;
        }
    }

    /**
     * @return void
     */
    public function prepareForValidation(): void
    {
        $inputs = $this->all();

        if(!$this->hasFile('img')){
            unset($inputs['img']);
        }

        if(isset($inputs['name']) && is_string($inputs['name'])){
            $inputs['name'] = json_decode($inputs['name'] , true);
        }

        $this->replace($inputs);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'img' => [
                $this->isUpdate ? 'sometimes' : 'required',
                'image',
                'mimes:jpg,png,jpeg,jfif',
                'max:10000' // 10 MB
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
