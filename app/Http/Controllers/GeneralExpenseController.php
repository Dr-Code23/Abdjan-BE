<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralExpenseRequest;
use App\Http\Resources\GeneralExpenseResource;
use App\Models\GeneralExpense;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class GeneralExpenseController extends Controller
{
    use HttpResponse;

    public function index()
    {
        return GeneralExpenseResource::collection(GeneralExpense::paginate(paginationCountPerPage()));
    }

    /**
     * @param GeneralExpenseRequest $request
     * @return JsonResponse
     */
    public function store(GeneralExpenseRequest $request): JsonResponse
    {
        return $this->createdResponse(
            new GeneralExpenseResource(
                GeneralExpense::create($request->validated())
            )
        );
    }


    /**
     * @param GeneralExpense $generalExpense
     * @return JsonResponse
     */
    public function show(GeneralExpense $generalExpense): JsonResponse
    {
        return $this->resourceResponse(
            new GeneralExpenseResource($generalExpense)
        );
    }


    /**
     * @param GeneralExpenseRequest $request
     * @param GeneralExpense $generalExpense
     * @return JsonResponse
     */
    public function update(GeneralExpenseRequest $request, GeneralExpense $generalExpense): JsonResponse
    {
        $generalExpense->update($request->validated());

        return $this->successResponse(
            new GeneralExpenseResource(
                $generalExpense
            ),
            translateSuccessMessage('general_expense', 'updated')
        );
    }

    /**
     * @param GeneralExpense $generalExpense
     * @return JsonResponse
     */
    public function destroy(GeneralExpense $generalExpense): JsonResponse
    {
        $generalExpense->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('general_expense', 'deleted')
        );
    }
}
