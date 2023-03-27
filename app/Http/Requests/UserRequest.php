<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    use HttpResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $excludeCurrentUser = (
            in_array($this->method() , ['PUT' , 'PATCH'])
                ? (','.($this->route('user')->id)).',id' : '');


        return [
            'name' => [
                'required' ,
                'string' ,
                'max:255'
            ],
            'email' => [
                'required' ,
                'email' ,
                'unique:users,email' . $excludeCurrentUser,
                'max:255'
            ]
            ,
            'password' => [
                in_array($this->method() , ['PUT' , 'PATCH']) ? 'sometimes' : 'required',
                'confirmed' ,
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'role_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('name','required'),
            'email.required' => translateErrorMessage('email','required'),
            'password.required' => translateErrorMessage('password','required'),
            'name.string' => translateErrorMessage('name' , 'invalid'),
            'name.max' => translateErrorMessage('name' , 'max.string'),
            'email.email' => translateErrorMessage('email' , 'email'),
            'email.unique' => translateErrorMessage('email' , 'unique'),
            'email.max' => translateErrorMessage('email' , 'max.string'),
            'password.confirmed' => translateErrorMessage('password' , 'confirmed'),
            'role_id.required' => translateErrorMessage('role_id' , 'required'),
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
