<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AdService
{
    public function index(): Collection|array
    {
        return Ad::with('image')
            ->latest('id')
            ->get();
    }

    public function show(int $id): Model|Builder|null
    {
        $ad = Ad::with('image')
            ->where('id' , $id)
            ->first();

        return $ad ?: null;
    }

    public function store(array $data){
        $errors = [];
        checkIfNameExists(
            Ad::class,

        );
    }
}
