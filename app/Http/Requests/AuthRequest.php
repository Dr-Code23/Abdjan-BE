<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'Your Email Cannot Be Empty',
            'password' => 'Your Password Cannot Be Empty'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->validationFailed($validator);
    }
}
