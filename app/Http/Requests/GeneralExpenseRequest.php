<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GeneralExpenseRequest extends FormRequest
{
    use HttpResponse;

    protected $stopOnFirstFailure = true;
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
            'price' => [
                'required' ,
                'numeric' ,
                'min:1'
            ],
            'reason' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => translateErrorMessage('price' , 'required'),
            'price.numeric' => translateErrorMessage('price' , 'numeric'),
            'price.min' => translateErrorMessage('price' , 'min.numeric'),
            'reason.required' => translateErrorMessage('reason' , 'required')
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
