<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Setting;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponse;

    /**
     * Authenticate User
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->validated();

        $user = User::whereHas('roles')
            ->with([
                'roles',
                'roles.permissions',
                'avatar'
            ])
            ->where('email' , $credentials['email'])
            ->where('status' , true)
            ->first();

        if($user){

            if(Hash::check($credentials['password'],$user->password)){
                $token = auth()->login($user);
                $permissions = [];
                foreach($user->roles->first()->permissions as $permission){
                    $permissions[] = $permission->name;
                }
                $loggedUser = auth()->user();
                $siteLogo = Setting::with('logo')->first(['id']);
                $siteLogo = $siteLogo->logo->first()->original_url ?? asset('/storage/default/logo.png');
                $response = [
                    'id' => $loggedUser->id,
                    'name' => $loggedUser->name,
                    'email' => $loggedUser->email,
                    'role_id' => $user->roles->first()->id,
                    'role_name' => $user->roles->first()->name,
                    'avatar' => $user->avatar->first()->original_url ?? asset('/storage/default/user.png'),
                    'siteLogo' => $siteLogo,
                    'token' => $token,
                    'permissions' => $permissions
                ];
                return $this->successResponse(
                    $response,
                    translateSuccessMessage('user' , 'logged_in')
                );

            }
        }

        return $this->unauthenticatedResponse(
            translateWord('wrong_credentials')
        );
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        session()->regenerate(true);
        return $this->successResponse(
            msg: translateSuccessMessage('user' , 'logged_out')
        );
    }
}
