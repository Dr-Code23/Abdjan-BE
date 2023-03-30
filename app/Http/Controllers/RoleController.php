<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use HttpResponse;
    public function __construct(
        private readonly RoleService $roleService
    ){

    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $rolesWithPermissions = $this->roleService->index();
        return $this->resourceResponse(
            RoleResource::collection($rolesWithPermissions)
        );
    }

    /**
     * @param int $role
     * @return JsonResponse
     */
    public function show(int $role): JsonResponse
    {
        $role = $this->roleService->show($role);

        if($role instanceof Role){
            return $this->resourceResponse(
                new RoleResource($role)
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('role' , 'not_found')
        );
    }

    /**
     * @param RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $result = $this->roleService->store($request->validated());

        if(is_bool($result) && $result){
            return $this->successResponse(
                msg:translateSuccessMessage('role' , 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    /**
     * @param RoleRequest $request
     * @param int $role
     * @return JsonResponse
     */
    public function update(RoleRequest $request , int $role): JsonResponse
    {
        $result = $this->roleService->update($request->validated() , $role);

        if(is_bool($result) && $result){
            return $this->successResponse(
                msg:translateSuccessMessage('role' , 'updated')
            );
        }

        else if(is_bool($result)){
            return $this->notFoundResponse(
                translateErrorMessage('role' , 'not_found')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function destroy(Role $role): JsonResponse
    {
        if($role->name != 'super_admin'){
            $role->delete();
            return $this->successResponse(
                msg:translateSuccessMessage('role' , 'deleted')
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('role' , 'not_found')
        );
    }
}
