<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\ProjectExpenseProduct;
use App\Models\ProjectMaterial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProjectExpenseService
{
    /**
     * @param int $project
     * @return Model|Builder|null
     */
    public function show(int $project): Model|Builder|null
    {
        $project = Project::with(
            [
                'project_expenses' => function ($query) {
                    $query->select(['id', 'project_id', 'created_at']);
                },
                'project_expenses.project_expense_product' => function($query) {
                    $query->select([
                        'id' ,
                        'product_id' ,
                        'project_expense_id',
                        'quantity',
                    ]);
                },
                'project_expenses.project_expense_product.product' => function($query){
                    $query->select(['id' , 'name']);
                },
            ]
        )
            ->where('id' , $project)
            ->first();

        return $project ?: null;
    }

    /**
     * @param array $data
     * @return Model|Builder|array|null
     */
    public function store(array $data): Model|Builder|array|null|bool
    {
        $errors = [];
        //TODO Check If The Project Exists
        $project = Project::where('id' , $data['project_id'])
            ->where('end_date' , '>' , date('Y-m-d'))
            ->first();

        if($project){

            //TODO Check If All Products Exists
            $allProductsIds = [];
            foreach($data['products'] as $product){
                $allProductsIds[] = $product['id'];
            }

            $products = Product::whereIn('id' , $allProductsIds)->get();
            $productsCount = $products->count();

            if($productsCount == count($allProductsIds)){

                for($i = 0 ; $i<$productsCount ; $i++){

                    $product = [];
                    $productIndex = 0;

                    ProjectService::setProductKeys(
                        $product ,
                        $productsCount ,
                        $products ,
                        $i ,
                        $data['products'],
                        $productIndex
                    );

                    $existingProductQuantity = $product['total_quantity'];
                    if($existingProductQuantity < $product['quantity']){
                        $errors["products.$productIndex.quantity"] =
                            translateErrorMessage(
                                'quantity' ,
                                'quantity.bigger'
                            ) . $existingProductQuantity;
                    }
                }

                if(!$errors){
                    //TODO everything is valid , so start updating product and insert the new rows.

                    //TODO Update Original Product Quantity

                    ProjectService::updateProductsAndGetTotal(
                        $productsCount,
                        $data['products'] ,
                        $allProductsIds ,
                    );

                    //TODO Store project expense

                    $projectExpense = ProjectExpense::create([
                        'project_id' => $project->id
                    ]);

                    for($i = 0 ; $i< $productsCount; $i++){
                        $data['products'][$i]['project_expense_id'] = $projectExpense->id;
                        $data['products'][$i]['product_id'] = $data['products'][$i]['id'];
                        unset(
                            $data['products'][$i]['id'],
                            $data['products'][$i]['total_quantity'],
                        );
                    }

                    ProjectExpenseProduct::insert($data['products']);

                    //TODO Find materials prices summation
                    $materialsPricesSum = ProjectMaterial::selectRaw('(sum(`quantity` * `price_per_unit`)) as sum')
                        ->value('sum');

                    //TODO Find Expenses Summation
                    $projectExpensesSum = ProjectExpenseProduct::selectRaw('(sum(`quantity` * `price_per_unit`)) as sum')
                        ->value('sum');

                    $project->update(['total' => $materialsPricesSum + $projectExpensesSum]);
                    return true;
                }
            } else {

                ProjectService::setProductNotFoundError($errors , $products , $data['products']);
            }
        } else {
            $errors['project'] = translateErrorMessage('project' , 'not_found');
        }

        return $errors;
    }
}


/*
 * Project Materials
 * Project Expense Products
 * Project Expense
 * */
