<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProjectPaymentRequest extends FormRequest
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
            'price' => [
                'required' ,
                'numeric' ,
                'min:0.1'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => translateErrorMessage('project' , 'required'),
            'price.required' => translateErrorMessage('price' , 'required'),
            'price.numeric' => translateErrorMessage('price' , 'numeric'),
            'price.min' => translateErrorMessage('price' , 'min.numeric'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
