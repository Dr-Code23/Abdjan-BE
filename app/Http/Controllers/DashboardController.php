<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;


class DashboardController extends Controller
{
    use HttpResponse;
    public function index(DashboardService $dashboardService): JsonResponse
    {

        $allPaymentsWithExpenses = [];
        $productsCount = $dashboardService->getProductsCount();
        $servicesCount = $dashboardService->getServicesCount();
        $dailyPayments = $monthlyPayments = $yearlyPayments = 0;
        $dailyExpenses = $monthlyExpenses = $yearlyExpenses = 0;
        $allProjectsCount = $doneProjectsCount = $pendingProjectsCount = 0;


        $dashboardService->addPaymentsPerDate($allPaymentsWithExpenses);
        $dashboardService->addExpensesPerDate($allPaymentsWithExpenses);
        $dashboardService->addPaymentsAndExpensesDetails(
            $allPaymentsWithExpenses,
            $dailyPayments,
            $monthlyPayments,
            $yearlyPayments,
            $dailyExpenses,
            $monthlyExpenses,
            $yearlyExpenses
        );

        $dashboardService->getProjectDetails(
            $allProjectsCount ,
            $pendingProjectsCount ,
            $doneProjectsCount
        );

        return $this->resourceResponse([
            'productsCount' => $productsCount,
            'servicesCount' => $servicesCount,
            'allProjectsCount' => $allProjectsCount,
            'pendingProjectsCount' => $pendingProjectsCount,
            'doneProjectsCount' => $doneProjectsCount,
            'dailyPayments' => round($dailyPayments , 2),
            'dailyExpenses' => round($dailyExpenses , 2),
            'monthlyPayments' => round($monthlyPayments , 2),
            'monthlyExpenses' => round($monthlyExpenses , 2),
            'yearlyPayments' => round($yearlyPayments , 2),
            'yearlyExpenses' => round($yearlyExpenses , 2)
        ]);
    }
}
