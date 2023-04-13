<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectPaymentRequest;
use App\Http\Resources\ProjectPayment\ProjectPaymentResource;
use App\Http\Resources\ProjectResource;
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

    public function index(ProjectService $projectService)
    {
        $projects = $projectService->projectWhereHasRelations(['project_payments']);

        return ProjectResource::collection($projects);
    }

    /**
     * @param ProjectPaymentRequest $request
     * @return JsonResponse
     */
    public function store(ProjectPaymentRequest $request): JsonResponse
    {
        $result = $this->projectWithPaymentService->store($request->validated());

        if ($result instanceof Project) {
            return $this->successResponse(
                translateSuccessMessage('project_payment' , 'created')
            );
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
            return $this->successResponse(
                translateSuccessMessage('project_payment' , 'updated')
            );
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

    public function showAllPayments(int $projectId): JsonResponse
    {
        return $this->resourceResponse(
            ProjectPayment::where('project_id' , $projectId)->get(['id as payment_id' , 'price' , 'created_at'])
        );
    }
}
