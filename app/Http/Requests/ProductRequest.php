<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasureUnit;
use App\Rules\TmpFileExists;
use App\Rules\ForeignKeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    use HttpResponse;

    public function prepareForValidation()
    {
        $inputs = $this->all();
        if($this->input('images') && !preg_match("/.*products$/",$this->url())){
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
        $rules = [
            'attribute_id' => [
                'required' ,
                new ForeignKeyExists((new Attribute())->getTable())
            ],
            'unit_id' => [
                'required' ,
                new ForeignKeyExists((new MeasureUnit())->getTable() , translateKey: 'unit')
            ],
            'category_id' => [
                'required' ,
                new ForeignKeyExists((new Category())->getTable() , translateKey: 'category')
            ],
            'brand_id' => [
                'required' ,
                new ForeignKeyExists((new Brand())->getTable())
            ],
            'quantity' => [
                'required' ,
                'integer' ,
                'min:1'
            ],
            'unit_price' => [
                'required' ,
                'numeric' ,
                'min:0.1'
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

        if(!preg_match("/.*products$/",$this->url())){

            $rules['images'][0] = 'sometimes';
            $rules['images.*'][0] = 'sometimes';

            // images to keep in update
            $rules['keep_images'] = ['sometimes' , 'array'];
            $rules['keep_images.*'] = ['sometimes' , 'string'];
        }

        addTranslationRules($rules , ['name' , 'description']);
        return $rules;
    }

    public function messages(): array
    {
        return [
            'attribute_id.required' => translateErrorMessage('attribute' , 'required'),
            'unit_id.required' => translateErrorMessage('unit' , 'required'),
            'category_id.required' => translateErrorMessage('category' , 'required'),
            'brand_id.required' => translateErrorMessage('brand' , 'required'),
            'quantity.required' => translateErrorMessage('quantity' , 'required'),
            'quantity.min' => translateErrorMessage('quantity' , 'min.numeric'),
            'quantity.integer' => translateErrorMessage('quantity' , 'integer'),
            'quantity.unit_price' => translateErrorMessage('unit_price' , 'required'),
            'unit_price.min' => translateErrorMessage('unit_price' , 'min.numeric'),
            'unit_price.numeric' => translateErrorMessage('unit_price' , 'numeric'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
