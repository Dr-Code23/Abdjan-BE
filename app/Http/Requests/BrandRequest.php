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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $excludeCurrentUser = in_array
        (
            $this->method() , ['PUT' , 'PATCH']
        )
        ? (',' . $this->route('brand')->id .',id')
        : '';

        return [
            'name' => [
                'required' ,
                'max:255',
                'unique:brands,name' . $excludeCurrentUser
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('name' , 'required'),
            'name.max' => translateErrorMessage('name' , 'max.string'),
            'name.unique' => translateErrorMessage('name' , 'unique')
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->validationFailed($validator);
    }
}

