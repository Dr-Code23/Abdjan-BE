<?php

namespace App\Services;

use App\Http\Controllers\UserController;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserService
{
    public function index(){

        return User::whereHas('roles' , fn($query) => $query->where('name' , '<>' , 'super_admin'))
            ->with(
                [
                    'roles' => fn($query) => $query->select(['id' , 'name' , 'guard_name']),
                    'avatar'
                ]
            )
            ->where('id' , '<>' , auth()->id())
            ->paginate(paginationCountPerPage());
    }

    public function show(int $id){
        $user = User::whereHas('roles' , fn($query) => $query->where('name' , '<>' , 'super_admin'))
            ->with(
                [
                    'roles' => fn($query) => $query->select(['id' , 'name' , 'guard_name']),
                    'avatar'
                ]
            )
            ->where('id' , '<>' , auth()->id())
            ->where('id' , $id)
            ->first();

        if($user){
            return $user;
        }

        return null;
    }

    public function store(array $data): User|bool|array
    {
        return $this->storeOrUpdateUser($data);
    }

    public function update(array $data , int $id): User|bool|array
    {
        return $this->storeOrUpdateUser($data , $id);
    }

    /**
     * Store Or Update User Logic
     *
     * @param array $data
     * @param int|null $user
     * @return User|array
     */
    protected function storeOrUpdateUser(array $data , int $user = null): bool|array
    {
        $errors = [];
        $fileOperationService = new FileOperationService();
        $inCreate = is_null($user);
        //TODO Check If Role Exists
        if(!$inCreate){
            $userObject = User::whereHas('roles' , fn($query) => $query->where('name' , '<>' , 'super_admin'))
                ->where('id' , $user)
                ->where('id' , '<>' , auth()->id())
                ->first();

            if(!$userObject){
                return false;
            }
        }

        if(!isset($userObject)){
            $userObject = new User();
        }
        $userObject->name = $data['name'];
        $userObject->email = $data['email'];

        if(isset($data['password'])){
            $userObject->password = $data['password'];
        }

        $role = Role::where('name' , '<>' , 'super_admin')
            ->where('id' , $data['role_id'])
            ->first();

        if($role){
            //TODO Store Or Update User Role

            is_null($user) ? $userObject->assignRole($role) : $userObject->syncRoles($role);

            $userObject->save();

            if(isset($data['avatar'])){
                //TODO delete the old image if in update
                if(!$inCreate){
                    $oldAvatar = $userObject->getFirstMedia();

                    if($oldAvatar) {
                        $oldAvatar->delete();
                    }
                }



                $fileOperationService->storeImageFromRequest(
                    $userObject ,
                    UserController::$collectionName,
                    'avatar',
                );
            }
            return true;
        } else {
            $errors['role_id'] = translateErrorMessage('role','not_found');
        }

        return $errors;
    }
}
