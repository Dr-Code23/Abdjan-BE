<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponse;
use App\Traits\RoleTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponse , RoleTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::whereHas('role' , fn($query) => $query->where('name' , '!=' , 'super_admin'))
            ->with('role')
            ->where('id' , '!=' , auth()->id())
            ->get();

        return $this->resourceResponse(UserResource::collection($users));
    }


    /**
     * Store User
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $result =  $this->storeOrUpdateUser($request->validated());

        if($result instanceof User){
            return $this->createdResponse(
                new UserResource($result) ,
                translateSuccessMessage('user' , 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }


    /**
     * Show One User
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $user = $user->load('role');
        if($user->role->name != 'super_admin') {

            return $this->resourceResponse(new UserResource($user));
        }

        return $this->notFoundResponse(translateErrorMessage('user' , 'not_found'));
    }


    /**
     * Update User
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $roleName = $this->getRoleNameById($user->role_id);

        if($user->id != auth()->id() && $roleName != 'super_admin')
        {
            $result = $this->storeOrUpdateUser($data , $user);

            if($result instanceof User){

                return $this->successResponse(
                    new UserResource($result) ,
                    translateSuccessMessage('user' , 'updated')
                );
            }

            return $this->validationErrorsResponse($result);
        }
        return $this->notFoundResponse(translateErrorMessage('user' , 'not_found'));
    }


    /**
     * Delete User
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        if($user->id != auth()->id() && $this->getRoleNameById($user->role_id) != 'super_admin'){
            $user->delete();

            return $this->successResponse(null , translateSuccessMessage('user' , 'deleted'));
        }

        return $this->notFoundResponse(translateErrorMessage('user' , 'not_found'));
    }


    /**
     * Store Or Update User Logic
     *
     * @param array $data
     * @param $user
     * @return User|array
     */
    private function storeOrUpdateUser(array $data , $user = null): User|array
    {

        //TODO Check If Role Exists
        $roleName = $this->getRoleNameById($data['role_id']);
        if($roleName && $roleName != 'super_admin'){
            //TODO Create Or Update User
            if(!$user) {
                $user = new User();
            }
            $user->name = $data['name'];
            $user->email = $data['email'];

            if(isset($data['password']) && $data['password']){
                $user->password = $data['password'];
            }
            $user->role_id = $data['role_id'];
            $user->save($data);
            $user->role_name = $roleName;

            return $user;

        }else {
            $errors['role'] = translateErrorMessage('role' , 'not_found');
        }

        return $errors;
    }
}
