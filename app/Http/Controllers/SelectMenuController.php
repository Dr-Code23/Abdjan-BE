<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SelectMenuController extends Controller
{
    use HttpResponse;
    public function brands(){
        return Brand::where('status' , true)->get(['id' , 'name']);
    }

    public function roles(){
        return $this->resourceResponse(
            Role::where('name' , '!=' , 'super_admin')->get(['id' , 'name'])
        );
    }
}
