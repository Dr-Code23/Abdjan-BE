<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Collection;

class AdService
{
    public function index(): Collection|array
    {
        return Ad::with('image')
            ->latest('id')
            ->get();
    }
}
