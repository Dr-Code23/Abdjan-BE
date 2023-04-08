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
            'productsCount' => number_format($productsCount),
            'servicesCount' => number_format($servicesCount),
            'allProjectsCount' => number_format($allProjectsCount),
            'pendingProjectsCount' => number_format($pendingProjectsCount),
            'doneProjectsCount' => number_format($doneProjectsCount),
            'dailyPayments' => number_format(round($dailyPayments , 2)),
            'dailyExpenses' => number_format(round($dailyExpenses , 2)),
            'monthlyPayments' => number_format(round($monthlyPayments , 2)),
            'monthlyExpenses' => number_format(round($monthlyExpenses , 2)),
            'yearlyPayments' => number_format(round($yearlyPayments , 2)),
            'yearlyExpenses' => number_format(round($yearlyExpenses , 2)),
            'dailyProfits' => ($dailyPayments - $dailyExpenses) > 0 ? number_format(round(($dailyPayments - $dailyExpenses) , 2)) : '0',
            'monthlyProfits' => ($monthlyPayments - $monthlyExpenses) > 0 ? number_format(round(($monthlyPayments - $monthlyExpenses) , 2)) : '0',
            'yearlyProfits' => ($yearlyPayments - $yearlyExpenses) > 0 ? number_format(round(($yearlyPayments - $yearlyExpenses) , 2)) : '0',
        ]);
    }
}
