<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\ProjectPayment;
use App\Models\Service;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * @return int
     */
    public function getProductsCount(): int
    {
        return Product::count();
    }

    /**
     * @return int
     */
    public function getServicesCount(): int{
        return Service::count();
    }

    public function addPaymentsPerDate(array & $allPaymentsWithExpenses): void
    {
        $projectsPayments = ProjectPayment::all(['price' ,'created_at']);
        foreach($projectsPayments as $projectsPayment){
            $formattedCreatedAt = (new Carbon($projectsPayment->created_at))->format('Y-m-d');

            if(isset($allPaymentsWithExpenses[$formattedCreatedAt]['payments_total'])){
                $allPaymentsWithExpenses[$formattedCreatedAt]['payments_total']+= $projectsPayment->price;
            } else {
                $allPaymentsWithExpenses[$formattedCreatedAt]['payments_total']= $projectsPayment->price;
            }
        }
    }

    public function addExpensesPerDate(array & $allPaymentsWithExpenses): void
    {
        $projectExpenses = ProjectExpense::with(['project_expense_product' => function($query){
            $query->select(['id' , 'project_expense_id' , 'quantity' , 'price_per_unit']);
        }])->get([
            'id',
            'created_at',
            'project_id'
        ]);

        foreach($projectExpenses as $projectExpense){
            $formattedCreatedAt = (new Carbon($projectExpense->created_at))->format('Y-m-d');

            foreach($projectExpense->project_expense_product as $product){
                if(isset($allPaymentsWithExpenses[$formattedCreatedAt]['project_expenses_total'])){
                    $allPaymentsWithExpenses[$formattedCreatedAt]['project_expenses_total']+=
                        ($product->quantity * $product->price_per_unit);
                } else {
                    $allPaymentsWithExpenses[$formattedCreatedAt]['project_expenses_total']=
                        ($product->quantity * $product->price_per_unit);
                }
            }
        }

    }

    public function addPaymentsAndExpensesDetails(
        array $allPaymentsWithExpenses ,
        int & $dailyPayments ,
        int & $monthlyPayments ,
        int & $yearlyPayments ,
        int & $dailyExpenses ,
        int & $monthlyExpenses ,
        int & $yearlyExpenses
    ): void
    {
        foreach($allPaymentsWithExpenses as $date => $details){
            $payments = $details['payments_total'] ?? 0;
            $expenses = $details['project_expenses_total'] ?? 0;

            if($date == date('Y-m-d')){
                $dailyPayments+= $payments;
                $dailyExpenses+= $expenses;
            }

            if($date <= date('Y-m-d' , strtotime('+30 days'))){
                $monthlyPayments+=$payments;
                $monthlyExpenses+=$expenses;
            }

            if($date <= date('Y-m-d',strtotime('+365 days'))){
                $yearlyPayments+=$payments;
                $yearlyExpenses+=$expenses;
            }
        }
    }

    public function getProjectDetails(
        int & $allProjectsCount ,
        int & $pendingProjectsCount ,
        int & $doneProjectsCount
    ): void
    {
        $allProjects = Project::all(['end_date']);

        foreach($allProjects as $project){
            $allProjectsCount++;
            if($project->end_date > now()){
                $pendingProjectsCount++;
            } else {
                $doneProjectsCount++;
            }
        }
    }
}
