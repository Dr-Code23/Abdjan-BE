<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    use HttpResponse;

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if(!$this->hasFile('avatar')) {
            unset($inputs['avatar']);
        }

        if(!isset($inputs['password'])){
            unset(
                $inputs['password'] ,
                $inputs['password_confirmation']
            );
        }

        $this->replace($inputs);
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
            'email' => [
                'required' ,
                'email' ,
                'unique:users,email,'.(auth()->id() .',id') ,
                'max:255'
            ],
            'password' => [
                'sometimes',
                'confirmed' ,
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'avatar' => [
                'sometimes' ,
                'file' ,
                'mimes:png,jpg,jfif' ,
                'max:10000'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => translateErrorMessage('name','required'),
            'email.required' => translateErrorMessage('email','required'),
            'name.string' => translateErrorMessage('name' , 'invalid'),
            'name.max' => translateErrorMessage('name' , 'max.string'),
            'email.email' => translateErrorMessage('email' , 'email'),
            'email.unique' => translateErrorMessage('email' , 'unique'),
            'email.max' => translateErrorMessage('email' , 'max.string'),
            'password.confirmed' => translateErrorMessage('password' , 'confirmed'),
            'avatar.file' => translateErrorMessage('avatar' , 'file'),
            'avatar.mimes' => translateErrorMessage('avatar' , 'mimes'),
            'avatar.max' => translateErrorMessage('avatar' , 'max.file'),
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
