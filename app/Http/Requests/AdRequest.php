<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AdRequest extends FormRequest
{
    use HttpResponse;
    protected bool $isUpdate = false;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        if(!preg_match("/.*ads$/",$this->url())){
            $this->isUpdate = true;
        }
    }

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if(isset($inputs['image']) && $this->isUpdate && !$inputs['image']){
            unset($inputs['image']);
        }

        if(isset($inputs['title']) && is_string($inputs['title'])){
            $inputs['title'] = json_decode($inputs['title'] , true);
        }

        if(isset($inputs['description']) && is_string($inputs['description'])){
            $inputs['description'] = json_decode($inputs['description'] , true);
        }

        $this->replace($inputs);
    }

    public function rules(): array
    {
        $rules =  [
            'discount' => ['required' , 'numeric' , 'min:0'],
            'image' => imageRules($this->isUpdate)
        ];

        addTranslatedKeysRules($rules);

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
