<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use HttpResponse;
    public function index(ProfileRequest $request , FileOperationService $fileOperationService): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('id' , auth()->id())->first();

        $image = $user->getFirstMedia(UserController::$collectionName);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if(isset($data['password'])){
            $user->password = $data['password'];
        }

        if($request->hasFile('avatar')){

            //TODO Delete The Old Image And Replace It With The New One

            $fileOperationService->removeImage($image);

            $image = $fileOperationService->storeImageFromRequest(
                $user,
                UserController::$collectionName,
                'avatar'
            );
        }

        $user->save();

        return $this->successResponse([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' =>$image->original_url ?? asset('storage/default/user.png'),
            'password_changed' => isset($data['password']),
            'avatar_changed' => isset($data['avatar']),
        ] ,
            translateSuccessMessage('profile' , 'updated')
        );
    }

    public function showProfileInfo(){
        $loggedUser = auth()->user();
        $loggedUser->load('avatar');
        $image = $loggedUser->avatar->first()->original_url ?? asset('/storage/default/user.png');

        return $this->resourceResponse(
            [
                'id' => $loggedUser->id,
                'name' => $loggedUser->name,
                'email' => $loggedUser->email,
                'avatar' => $image,
            ]
        );
    }
}
