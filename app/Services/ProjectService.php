<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectMaterial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function index(): Collection|array
    {
        return Project::all([
            'id',
            'project_name',
            'customer_name',
        ]);
    }

    public function show($project): Model|Builder|null
    {
        $project = Project::with(
                [
                    'materials',
                    'materials.product' => fn($query) => $query->select(['id' , 'name'])
                ]
            )
        ->where('id' , $project)
        ->first();

        if($project){
            return $project;
        }

        return null;
    }

    /**
     * @param array $data
     * @return array|Project
     */
    public function store(array $data): array|Project
    {
        //TODO Checking If The Project Exists
        $projectExists = Project::where('customer_name' , $data['customer_name'])
            ->where('project_name' , $data['project_name'])
            ->where('start_date' , $data['start_date'])
            ->first(['id']);

        $errors = [];

        if(!$projectExists){
            //TODO Checking If All Materials Exists With Enough Quantity

            $allMaterialsIds = [];
            foreach($data['materials'] as $material){
                $allMaterialsIds[] = $material['id'];
            }
            $products = Product::whereIn('id' , $allMaterialsIds)->get(['id' , 'quantity' , 'unit_price']);

            $productsCount = $products->count();
            if($productsCount == count($allMaterialsIds)){
                //then all materials exists, so start checking the quantity
                for($i = 0 ; $i<$productsCount ; $i++){
                    $existingProductQuantity = $products[$i]->quantity;
                    if($existingProductQuantity < $data['materials'][$i]['quantity']){
                        // quantity is bigger than existing which is (x)
                        $errors["materials.$i.quantity"] =
                            translateErrorMessage('quantity' , 'quantity.bigger') . $existingProductQuantity;
                    }
                }

                if(!$errors){
                    //TODO Update Original Product Quantity

                    $total = 0;
                    $caseStatement = "case `id`";
                    for($i = 0 ; $i<$productsCount ; $i++){
                        $total+= ($data['materials'][$i]['quantity'] * $products[$i]->unit_price);
                        $caseStatement.=" when " . $products[$i]->id ." then " . $products[$i]->quantity;
                    }
                    $caseStatement.=" else `quantity` end ";
                    //ex => case id when 1 then 1 else quantity end

                    $query = "update products set `quantity` = $caseStatement where `id` in ";
                    DB::update(
                        $query .
                        (
                            '(' . implode(',' , $allMaterialsIds) . ')'
                        )
                    );

                    //TODO Store The Project
                    $project = Project::create([
                        'customer_name' => $data['customer_name'],
                        'project_name' => $data['project_name'],
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        'total' => $total
                    ]);

                    for($i=0 ; $i<$productsCount ; $i++){
                        $data['materials'][$i]['project_id'] = $project->id;
                        $data['materials'][$i]['product_id'] = $data['materials'][$i]['id'];
                        unset($data['materials'][$i]['id']);
                    }


                    ProjectMaterial::insert($data['materials']);

                    $this->loadOnProject($project);

                    return $project;

                }
            }

        }else {
            $errors['project'] = translateErrorMessage('project' , 'exists');
        }

        return $errors;
    }

    /**
     * @param Project $project
     * @return Project
     */
    protected function loadOnProject(Project $project): Project
    {
        $project->load([
            'materials',
            'materials.product' => fn($query) => $query->select(['id' , 'name'])
        ]);

        return $project;
    }
}
