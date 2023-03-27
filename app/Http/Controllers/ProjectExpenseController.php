<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectExpenseRequest;
use App\Http\Resources\BasicProjectResource;
use App\Http\Resources\ProjectExpense\ProjectWithExpensesResource;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Services\ProjectExpenseService;
use App\Services\ProjectService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ProjectExpenseController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly ProjectExpenseService $expenseService)
    {

    }

    /**
     * @param ProjectService $projectService
     * @return JsonResponse
     */
    public function index(ProjectService $projectService): JsonResponse
    {
        $projects = $projectService->projectWhereHasRelations(['project_expenses']);

        return $this->resourceResponse(
            BasicProjectResource::collection($projects)
        );
    }


    /**
     * @param ProjectExpenseRequest $request
     * @return JsonResponse
     */
    public function store(ProjectExpenseRequest $request): JsonResponse
    {
        $project = $this->expenseService->store($request->validated());

        if ($project instanceof Project) {

            return $this->resourceResponse(new ProjectWithExpensesResource($project));
        } else if ($project == null) {

            return $this->notFoundResponse(
                translateErrorMessage('project', 'not_found')
            );
        }

        return $this->validationErrorsResponse($project);
    }

    /**
     * @param int $project
     * @return JsonResponse
     */
    public function show(int $project): JsonResponse
    {
        $project = $this->expenseService->show($project);

        if ($project instanceof Project) {

            return $this->resourceResponse(new ProjectWithExpensesResource($project));
        }

        return $this->notFoundResponse(
            translateErrorMessage('project', 'not_found')
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectExpense $projectExpense)
    {
        //
    }
}
