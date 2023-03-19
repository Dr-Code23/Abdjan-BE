<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MeasurementUnitRequest extends FormRequest
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
        $excludeCurrentUser = '';
        if(in_array($this->method() , ['PUT' , 'PATCH'])){
            $excludeCurrentUser = ',' . ($this->route('unit')->id).',id';
        }
        return [
            'name' => [
                'required' ,
                'unique:measurement_units,name' . $excludeCurrentUser
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('attribute' , 'required'),
            'name.unique' => translateErrorMessage('attribute' , 'unique'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
