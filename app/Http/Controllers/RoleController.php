<?php

namespace App\Http\Controllers;

use App\Http\Resources\NameWithIdResource;
use App\Models\Role;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return NameWithIdResource::collection(Role::all());
    }
}
