<?php

namespace App\Services;

use App\Facades\Search;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectMaterial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function index()
    {
        return Project::select([
            'id',
            'project_name',
            'customer_name',
        ])
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['project_name' , 'customer_name' , 'start_date' , 'end_date'] ,
                    request('handle')
                );
            })
            ->paginate(paginationCountPerPage());
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
            $products = Product::whereIn('id' , $allMaterialsIds)
                ->get(['id' , 'quantity' , 'unit_price']);


            $productsCount = $products->count();
            if($productsCount == count($allMaterialsIds)){
                //then all materials exists, so start checking the quantity

                //TODO Find The Product That Has Current Id
                for($i = 0 ; $i<$productsCount ; $i++){
                    $product = [];
                    $productIndex = 0;

                    static::setProductKeys(
                        $product ,
                        $productsCount,
                        $products,
                        $i,
                        $data['materials'],
                        $productIndex,
                    );

                    $existingProductQuantity = $product['total_quantity'];
                    if($existingProductQuantity < $product['quantity']){
                        // ex => quantity is bigger than existing which is (x)
                        $errors["materials.$productIndex.quantity"] =
                            translateErrorMessage(
                                'quantity' ,
                                'quantity.bigger'
                            ) . $existingProductQuantity;
                    }
                }

                if(!$errors){
                    //TODO Update Original Product Quantity
                    $total = static::updateProductsAndGetTotal(
                        $productsCount ,
                        $data['materials'] ,
                        $allMaterialsIds ,
                    );

                    //TODO Store The Project
                    $project = Project::create([
                        'customer_name' => $data['customer_name'],
                        'project_name' => $data['project_name'],
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        'total' => $total,
                        'project_total' => $data['project_total'],
                    ]);

                    //TODO Prepare To Insert Project Materials
                    for($i=0 ; $i<$productsCount ; $i++){
                        $data['materials'][$i]['project_id'] = $project->id;
                        $data['materials'][$i]['product_id'] = $data['materials'][$i]['id'];

                        unset(
                            $data['materials'][$i]['id'],
                            $data['materials'][$i]['total_quantity']
                        );
                    }

                    ProjectMaterial::insert($data['materials']);

                    $this->loadOnProject($project);

                    return $project;

                }
            } else {
                static::setProductNotFoundError($errors , $products , $data['materials']);
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

    public function projectWhereHasRelations(array $relations)
    {
        return Project::where(function($query) use ($relations){
            foreach($relations as $relation){
                $query->whereHas($relation);
            }
        })
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['project_name' , 'customer_name' , 'start_date' , 'end_date'] ,
                    request('handle')
                );
            })
            ->latest('id')
            ->select(['id' , 'project_name' , 'customer_name'])
            ->paginate(
                paginationCountPerPage()
            );
    }

    public static function updateProductsAndGetTotal(
        int        $productsCount ,
        array      $materials ,
        array      $allProductsIds ,
    ): float|int
    {
        $total = 0;
        $caseStatement = "case `id`";
        for($i = 0 ; $i<$productsCount ; $i++){
            // info($materials[$i]);
            $total+= ($materials[$i]['quantity'] * $materials[$i]['price_per_unit']);
            $caseStatement.=" when " . $materials[$i]['id'] . " then " . ($materials[$i]['total_quantity'] - $materials[$i]['quantity']);
        }
        $caseStatement.=" else `quantity` end ";
        //ex => case id when 1 then 1 else quantity end

        $query = "update products set `quantity` = $caseStatement where `id` in ";


        DB::update(
            $query .
            (
                '(' . implode(',' , $allProductsIds) . ')'
            )
        );
        return $total;
    }

    public static function setProductKeys
    (
        array &$product ,
        int $productsCount,
        Collection $products,
        int $i,
        array & $materials,
        int & $productIndex,
    ): void
    {
        /*
         * Get Current Product Information => O(N2)
         *
         * I made this loop because the order of `$allMaterialsIds` may be different
         * compared with `$products`
         *
         *
         * */
        for($j = 0 ; $j < $productsCount ; $j++){
            if($materials[$j]['id'] == $products[$i]->id){
                /*
                 * id
                 * quantity
                 * total quantity
                 * unit_price
                 *
                 * */
                $product = $materials[$j];
                $product['total_quantity'] = $products[$i]->quantity;
                $product['price_per_unit'] = $products[$i]->unit_price;
                $materials[$j]['price_per_unit'] = $products[$i]->unit_price;
                $materials[$j]['total_quantity'] = $products[$i]->quantity;
                $productIndex = $j;
                break;
            }
        }
    }

    public static function setProductNotFoundError(array & $errors , $products , array $dataProducts): void
    {
        $existingProducts = [];
        foreach($products as $product){
            $existingProducts[] = $product->id;
        }
        for ($i = 0 ; $i< count($dataProducts) ; $i++){

            if(!in_array($dataProducts[$i]['id'] , $existingProducts)){
                $errors["products.$i.id"] = translateErrorMessage('product' , 'not_found');
            }
        }
    }
}
