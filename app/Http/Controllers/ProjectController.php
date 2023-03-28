<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    use HttpResponse;
    public function __construct(private readonly ProjectService $projectService){}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            ProjectResource::collection($this->projectService->index())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        $result = $this->projectService->store($request->validated());

        if($result instanceof Project){
            return $this->createdResponse(new ProjectResource($result));
        }

        return $this->validationErrorsResponse($result);
    }

    /**
     * Display the specified resource.
     */
    public function show($project): JsonResponse
    {
        $project = $this->projectService->show($project);

        if($project instanceof Project){
            return $this->resourceResponse(new ProjectResource($project));
        }

        return $this->notFoundResponse(
            translateErrorMessage('project' , 'not_found')
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return $this->successResponse(
            translateSuccessMessage('project' , 'deleted')
        );
    }
}
