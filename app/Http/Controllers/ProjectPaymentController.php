<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectPaymentRequest;
use App\Http\Resources\ProjectPayment\ProjectPaymentResource;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Services\ProjectService;
use App\Services\ProjectWithPaymentService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ProjectPaymentController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly ProjectWithPaymentService $projectWithPaymentService)
    {
    }

    /**
     * @param ProjectService $projectService
     * @return JsonResponse
     */
    public function index(ProjectService $projectService): JsonResponse
    {
        $projects = $projectService->projectWhereHasRelations(['project_payments']);

        return $this->resourceResponse($projects);
    }

    /**
     * @param ProjectPaymentRequest $request
     * @return JsonResponse
     */
    public function store(ProjectPaymentRequest $request): JsonResponse
    {
        $result = $this->projectWithPaymentService->store($request->validated());

        if ($result instanceof Project) {
            return $this->createdResponse(new ProjectPaymentResource($result));
        }

        return $this->validationErrorsResponse($result);
    }


    /**
     * @param int $project
     * @return JsonResponse
     */
    public function show(int $project): JsonResponse
    {
        $project = $this->projectWithPaymentService->show($project);

        if ($project instanceof Project) {
            return $this->resourceResponse(new ProjectPaymentResource($project));
        }

        return $this->notFoundResponse(
            translateErrorMessage('project', 'not_found')
        );
    }

    /**
     * @param ProjectPaymentRequest $request
     * @param int $projectPayment
     * @return JsonResponse
     */
    public function update(ProjectPaymentRequest $request, int $projectPayment): JsonResponse
    {
        $result = $this->projectWithPaymentService->update($request->validated(), $projectPayment);

        if ($result instanceof Project) {
            return $this->successResponse(new ProjectPaymentResource($result));
        }
        return $this->validationErrorsResponse($result);
    }

    /**
     * @param ProjectPayment $projectPayment
     * @return JsonResponse
     */
    public function destroy(ProjectPayment $projectPayment): JsonResponse
    {
        $projectPayment->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('project_payment', 'deleted')
        );
    }
}
