<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Traits\HttpResponse;
use App\Traits\RoleTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $credentials = $request->validated();

        if($token = auth()->attempt($credentials)){
            $user = auth()->user();
            return $this->success([
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => asset('storage/users/'.($user->avatar ?:'default.png')),
                'token' => $token
            ] , 'User Logged In Successfully'
            );
        }

        return $this->unauthenticatedResponse('Wrong Credentials');
    }
}
