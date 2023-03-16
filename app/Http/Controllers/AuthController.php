<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Traits\HttpResponse;
use App\Traits\RoleTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use HttpResponse , RoleTrait;

    /**
     * Authenticate User
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if($token = auth()->attempt($request->validated()))
        {
            $user = auth()->user();
            $user = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'role_name' => $this->getRoleNameById($user->role_id),
                'avatar' => asset('storage/users/'.($user->avatar ?:'default.png')),
                'token' => $token
            ];

            return $this->success($user, 'User Logged In Successfully');
        }

        return $this->unauthenticatedResponse('Wrong Credentials');
    }
}
