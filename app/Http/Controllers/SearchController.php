<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\MeasureUnit;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * @var array|array[]
     */
    protected array $allowedModelToSearch = [
        'users' => [
            'model' => User::class,
            'translated' => false,
            'searchableKeys' => ['email' , 'name']
        ],
        'products' => [
            'model' => Product::class,
            'translated' => true,
            'searchableKeys' => ['name' , 'description'],
        ],
        'attributes' => [
            'model' => Attribute::class,
            'translated' => false,
            'searchableKeys' => ['name']
        ],
        'units' => [
            'model' => MeasureUnit::class,
            'translated' => false,
            'searchableKeys' => ['name']
        ],
        'projects' => [
            'model' => Project::class,
            'translated' => false,
            'searchableKeys' => ['customer_name' , 'project_name']
        ]
    ];


    public function handle(){

        $errors = [];
        $handle = request('handle');
        $allowedModelsToSearch = $this->allowedModelToSearch;
        if(isset($allowedModelsToSearch[$handle])){
            $model = new ($allowedModelsToSearch['handle']['model']);
            $model->where(function($query) use ($allowedModelsToSearch , $handle){
                $isFirstKey = false;
                foreach($allowedModelsToSearch[$handle]['searchableKeys'] as $key){
                    if($allowedModelsToSearch[$handle]['translated']){
                        // It's Translated Model So Start
                    }
                    if(!$isFirstKey) {
                        $query->where($key , $handle);
                        $isFirstKey = true;
                    } else {
                        $query->orWhere($key , $handle);
                    }
                }
            });
        } else {
            $errors['handle'] = translateErrorMessage('handle' , 'not_found');
        }

        return $errors;
    }
}
