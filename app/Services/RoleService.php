<?php

namespace App\Services;

use App\Facades\Search;
use App\Http\Requests\RoleRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class RoleService
{

    public function index()
    {
        return Role::whereNot('name' , 'super_admin')
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['name'] ,
                    request('handle')
                );
            })
            ->paginate(paginationCountPerPage());
    }

    /**
     * @param int $id
     * @return Model|Builder|null
     */
    public function show(int $id)
    {
        $role = Role::with(
            ['permissions' => function($query){
                $query->select(['id' , 'name']);
            }]
        )
            ->where('id' , $id)
            ->where('name' , '<>','super_admin')
            ->first(['id', 'name', 'created_at']);

        if($role){
            $allPermissions = Permission::all(['id' , 'name']);
            $rolePermissions = [];
            foreach($role->permissions as $permission){
                $rolePermissions[] = $permission->id;
            }

            for($i = 0 ; $i<count($allPermissions) ; $i++){
                $allPermissions[$i]->status = in_array($allPermissions[$i]->id , $rolePermissions);
            }
            $role->custom_permissions = $allPermissions;
            return $role;
        }

        return null;
    }

    /**
     * @param array $data
     * @return bool|array
     */
    public function store(array $data): bool|array
    {
        return $this->storeOrUpdate($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool|array
     */
    public function update(array $data , int $id): bool|array
    {
        return $this->storeOrUpdate($data , $id);
    }

    /**
     * @param array $data
     * @param int|null $id
     * @return bool|array
     */
    protected function storeOrUpdate(array $data , int $id = null): bool|array
    {
        $errors =[];

        if($id){
            $role = Role::where('id' , $id)
                ->where('name' , '<>' , 'super_admin')
                ->first();
            if(!$role){
                return false;
            }
        }

        $roleExists = Role::where('name', $data['name'])
            ->where(function ($query) use ($id) {
                if (!is_null($id)) {
                    $query->where('id', '<>',$id);
                }
            })
            ->first(['id']);

        if (!$roleExists) {
            //TODO Check If All Permissions Exists
            $requestPermissions = $data['permissions'];
            $permissions = Permission::whereIn('id', $requestPermissions)->get();
            $requestPermissionsCount = count($requestPermissions);

            if ($requestPermissionsCount == $permissions->count()) {
                $payload = ['name' => $data['name']];

                if(!isset($role)){
                    $role = Role::create($payload);

                    $role->givePermissionTo($permissions);
                } else {

                    $role->update($payload);

                    $role->syncPermissions($requestPermissions);
                }

                return true;

            } else {
                //TODO Get Existing Permissions Ids
                $existingPermissions = [];
                foreach ($permissions as $permission) {
                    $existingPermissions[] = $permission->id;
                }

                //TODO Generate Errors For Permissions
                for ($i = 0; $i < $requestPermissionsCount; $i++) {
                    if (!in_array($requestPermissions[$i], $existingPermissions)) {
                        $errors["permissions.$i.id"]
                            = translateErrorMessage('permission', 'not_found');
                    }
                }
            }
        } else {
            $errors['name'] = translateErrorMessage('role', 'exists');
        }
        return $errors;
    }
}
