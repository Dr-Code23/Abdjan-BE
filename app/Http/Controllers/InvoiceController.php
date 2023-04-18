<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\Setting;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    public function projectExpenses(ProjectExpense $projectExpense){
        $project = Project::with(
            [
                'project_expenses' => function($query) use ($projectExpense){
                    $query->select(['id' , 'project_id' , 'created_at']);
                    $query->where('id' , $projectExpense->id);
                    $query->with(
                        [
                            'project_expense_product',
                            'project_expense_product.product' => function($query){
                                $query->select(['id' , 'name' , 'description']);
                            }
                        ]
                    );
                }
            ]
        )
            ->where('id' , $projectExpense->project_id)
            ->select(['id' , 'project_name' , 'customer_name'])
            ->first();

        $storeInfo = Setting::find(1);
        $project->email = $storeInfo->email;
        $project->phone = explode('/',$storeInfo->phones)[0] ?? null;
        return new InvoiceResource($project , 'project_expense');
        return $project;
    }
}
