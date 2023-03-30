<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'message' => ['required']
        ];
    }

    public function messages(): array
    {
        $messages = [];

        foreach(array_keys($this->rules()) as $key){
            $messages["$key.required"] = translateErrorMessage($key , 'required');
        }

        return $messages;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
