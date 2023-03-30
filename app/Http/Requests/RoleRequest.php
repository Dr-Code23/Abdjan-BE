<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required' , 'alpha_dash:ascii'],
            'permissions' => ['required' , 'array'],
            'permissions.*' => ['required' , 'numeric' , 'distinct']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('name' , 'required'),
            'name.alpha_dash' => translateErrorMessage('name' , 'alpha_dash'),
            'permissions.required' => translateErrorMessage('permissions' , 'required'),
            'permissions.array' => translateErrorMessage('permissions' , 'array'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
