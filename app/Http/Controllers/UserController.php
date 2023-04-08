<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'users';

    public function __construct(private readonly UserService $userService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->index();

        return UserResource::collection($users);

    }


    /**
     * Store User
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $result =  $this->userService->store($request->validated());

        if(is_bool($result) && $result){
            return $this->createdResponse(
                msg: translateSuccessMessage('user' , 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }


    /**
     * @param int $user
     * @return JsonResponse
     */
    public function show(int $user): JsonResponse
    {
        $user = $this->userService->show($user);
        if($user instanceof User) {
            return $this->resourceResponse(new UserResource($user));
        }

        return $this->notFoundResponse(
            translateErrorMessage('user' , 'not_found')
        );
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $result = $this->userService->update($request->validated() , $user->id);

       if(is_bool($result) && $result){

           return $this->successResponse(
             msg:translateSuccessMessage('user' , 'updated')
           );

       } else if (is_bool($result)){
           return $this->notFoundResponse(translateErrorMessage('user' , 'not_found'));
       }

       return $this->validationErrorsResponse($result);

    }

    /**
     * Delete User
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->loadMissing('roles');
        $roleName = $user->roles->first()->name;

        if($user->id != auth()->id() && $roleName != 'super_admin'){
            $user->delete();

            return $this->successResponse(
                msg: translateSuccessMessage('user' , 'deleted')
            );

        }

        return $this->notFoundResponse(
            translateErrorMessage('user' , 'not_found')
        );
    }
}
