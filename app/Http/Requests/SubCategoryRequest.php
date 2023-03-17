<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
        return [
            'name' => [
                'required' ,
                'string' ,
                'max:255'
            ],
            'parent_id' => [
                'required'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('name' , 'required'),
            'name.string' => translateErrorMessage('name' , 'string'),
            'name.max' => translateErrorMessage('name' , 'max.string'),
            'parent_id.required' => translateErrorMessage('parent_category' , 'required')
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->validationFailed($validator);
    }
}
