<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SelectMenuController extends Controller
{
    public function brands(){
        return Brand::where('status' , true)->get(['id' , 'name']);
    }

    public function roles(){

    }
}
