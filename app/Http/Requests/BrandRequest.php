<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BrandRequest extends FormRequest
{
    use HttpResponse;
    protected bool $isUpdate = false;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        if(!preg_match("/.*brands$/",request()->url())){
            $this->isUpdate = true;
        }
    }

    public function prepareForValidation()
    {
        $data = $this->all();
        if($this->isUpdate){
            if(!$this->file('img')){
                unset($data['img']);
            }
        }

        if(isset($data['name']) && is_string($data['name'])) {
            $data['name'] = json_decode($data['name'], true);
        }


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
            'img' => imageRules($this->isUpdate)
        ];

        addTranslationRules($rules);

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}

