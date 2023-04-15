<?php

namespace App\Http\Requests;

use App\Rules\TmpFileExists;
use App\Rules\ForeignKeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ServiceRequest extends FormRequest
{
    use HttpResponse;

    private bool $isUpdate = false;

    protected $stopOnFirstFailure = true;

    public function __construct()
    {

        parent::__construct();

        if(!preg_match("/.*services$/i", request()->url()))
        {
            $this->isUpdate = true;
        }


    }

    public function prepareForValidation()
    {
        $inputs = $this->all();
        if($this->input('images') && $this->isUpdate){
            if(!$this->input('images')){
                unset($inputs['images']);
            }
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
        $rules =  [
            'price' => [
                'required' ,
                'numeric' ,
                'min:0.1'
            ],
            'phone' => [
                'required'
            ],
            'category_id' => [
                'required' ,
                new ForeignKeyExists('categories' , translateKey: 'category')
            ],
            'images' => [
                'required',
                'array'
            ],
            'images.*' => [
                'required' ,
                'string',
                new TmpFileExists()
            ]
        ];

        if($this->isUpdate){

            $rules['images'][0] = 'sometimes';
            $rules['images.*'][0] = 'sometimes';

            // images to keep in update
            $rules['keep_images'] = ['sometimes' , 'array'];
            $rules['keep_images.*'] = ['sometimes' , 'string'];
        }

        ProductRequest::addKeepImagesOnUpdate();
        addTranslationRules($rules , ['name' , 'description']);

        return $rules;
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        $messages =  [
            'price.required' => translateErrorMessage('price' , 'required'),
            'price.numeric' => translateErrorMessage('price' , 'numeric'),
            'price.min' => translateErrorMessage('price' , 'min.numeric'),
            'phone.required' => translateErrorMessage('phone' , 'required'),
            'category_id.required' => translateErrorMessage('category' , 'required'),
        ];

        addCustomTranslationMessages($messages , ['name' , 'description']);

        return $messages;
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
