<?php

namespace App\Http\Requests;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasureUnit;
use App\Rules\ForeignKeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
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

        ];

//        foreach(config('translatable.locales') as $locale){
//            $titleRules = "string|max:255";
//            $descriptionRules = "string|nullable";
//
//            if($locale == app()->getLocale()){
//                $descriptionRules = "required|".$descriptionRules;
//                $titleRules = "required|".$titleRules;
//            }
//            else {
//                $titleRules = "sometimes|" . $titleRules;
//                $descriptionRules = "sometimes|".$descriptionRules;
//            }
//
//            $rules['title:'.$locale] = $titleRules;
//            $rules['description:'.$locale] = $descriptionRules;
//        }
        addTranslationRules($rules);
        info($rules);
        return $rules;
    }

    public function messages(): array
    {
        $messages = [
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

        foreach(config('translatable.locales') as $locale){
            $messages['title:'.$locale.".required"] = translateErrorMessage('title' , 'required');
            $messages['title:'.$locale.".string"] = translateErrorMessage('title' , 'string');
            $messages['title:'.$locale.".max"] = translateErrorMessage('title' , 'max.string');
            $messages['description:'.$locale.".required"] = translateErrorMessage('description' , 'required');
            $messages['description:'.$locale.".string"] = translateErrorMessage('description' , 'string');
        }

        return $messages;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
