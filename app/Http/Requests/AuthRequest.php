<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class AuthRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'email' => translateErrorMessage('email' , 'required'),
            'password' => translateErrorMessage('password' , 'required')
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $this->validationFailed($validator);
    }
}
