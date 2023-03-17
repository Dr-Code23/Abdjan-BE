<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use App\Traits\FileOperationTrait;
use App\Traits\RoleTrait;

class ProfileController extends Controller
{
    use FileOperationTrait , RoleTrait;
    public function index(ProfileRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password){
            $user->password = $request->password;
        }
        if($request->hasFile('avatar')){
            //TODO Delete The Old Image And Replace It With The New One
            if($this->deleteImage('users/'.$user->avatar)){
                $image = explode('/' , $request->file('avatar')->store('public/users'));
                $imageName = $image[count($image)-1];
                $user->avatar = $imageName;
            }
        }

        $user->save();

        return $this->successResponse([
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_name' => $this->getRoleNameById($user->role_id),
            'password_changed' => $request->has('password'),
            'avatar' => asset('storage/users/'.($user->avatar ?:'default.png')),
        ] ,
            translateSuccessMessage('profile' , 'updated')
        );
    }

}
