<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    use HttpResponse;

    protected bool $isUpdate = false;
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        if(!preg_match( "/.*users$/" , request()->url())){
            $this->isUpdate = true;
        }
    }

    public function prepareForValidation()
    {
        $inputs = $this->all();

        if(isset($inputs['avatar'])){
            if(!$inputs['avatar'] && $this->isUpdate){
                unset($inputs['avatar']);
            }
        }

        if(isset($inputs['password']) && $this->isUpdate && !$inputs['password']){
            unset($inputs['password']);
            unset($inputs['password_confirmation']);
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
        $excludeCurrentUser = (
            $this->isUpdate
                ? (','.($this->route('user')->id)).',id'
                : ''
        );


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
            'password' => passwordRules($this->isUpdate),
            'role_id' => [
                'required'
            ],
            'avatar' => imageRules($this->isUpdate)
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
