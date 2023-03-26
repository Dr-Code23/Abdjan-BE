<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProjectExpenseRequest extends FormRequest
{
    use HttpResponse;
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required'],
            'products' => ['required' , 'array'],
            'products.*.id' => ['required' , 'distinct'],
            'products.*.quantity' => ['required' , 'numeric' , 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => translateErrorMessage('project' , 'required'),
            'products.required' => translateErrorMessage('products' , 'required'),
            'products.array' => translateErrorMessage('products' , 'array'),
        ];
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
