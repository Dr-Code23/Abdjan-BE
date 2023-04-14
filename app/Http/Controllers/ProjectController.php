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


    public function index()
    {
        return ProjectResource::collection($this->projectService->index());
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $result = $this->projectService->store($request->validated());

        if($result instanceof Project){
            return $this->successResponse(
                msg:translateSuccessMessage('project' , 'created')
            );
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
