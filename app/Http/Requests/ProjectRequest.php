<?php

namespace App\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'project_name' => [
                'required' ,
                'string' ,
                'max:255'
            ],
            'customer_name' => [
                'required' ,
                'string' ,
                'max:255'
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
            ],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after:start_date'
            ],
            'materials' => [
                'required',
                'array'
            ],
            'materials.*.id' => [
                'required' ,
                'numeric',
                'distinct'
            ],
            'materials.*.quantity' => [
                'required' ,
                'numeric' ,
                'min:1'
            ],
            'project_total' => [
                'required' ,
                'numeric' ,
                'min:1'
            ]
        ];
    }

    public function messages(): array
    {
        $messages = [
            'end_date.after' => translateErrorMessage('end_date' , 'after'),
            'materials.array' =>translateErrorMessage('materials' , 'array'),
            'materials.*.id.distinct' => translateErrorMessage('material' , 'distinct'),
            'materials.*.quantity.numeric' => translateErrorMessage('quantity' , 'numeric'),
            'materials.*.quantity.min' => translateErrorMessage('quantity' , 'min.numeric'),
        ];

        foreach(array_keys($this->rules()) as $input){
            $messages["$input.required"] = translateErrorMessage($input , 'required');
        }

        foreach(['project_name' , 'customer_name'] as $input){
            $messages["$input.string"] = translateErrorMessage($input, 'string');
            $messages["$input.max"] = translateErrorMessage($input, 'max.string');
        }

        foreach(['start_date' , 'end_date'] as $input){
            $messages["$input.date_format"] = translateErrorMessage($input , 'date_format');
        }

        return $messages;
    }

     protected function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
