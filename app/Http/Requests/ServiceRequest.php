<?php

namespace App\Http\Requests;

use App\Rules\ForeignKeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ServiceRequest extends FormRequest
{
    use HttpResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules =  [
            'price' => [
                'required' ,
                'numeric' ,
                'min:0.1'
            ],
            'phone' => [
                'required'
            ],
            'category_id' => ['required' , new ForeignKeyExists('categories' , translateKey: 'category')]
        ];
        addTranslationRules($rules);

        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages =  [
            'price.required' => translateErrorMessage('price' , 'required'),
            'price.numeric' => translateErrorMessage('price' , 'numeric'),
            'price.min' => translateErrorMessage('price' , 'min.numeric'),
            'phone.required' => translateErrorMessage('phone' , 'required'),
            'category_id.required' => translateErrorMessage('category' , 'required'),
        ];

        addCustomTranslationMessages($messages , ['name' , 'description']);

        return $messages;
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
