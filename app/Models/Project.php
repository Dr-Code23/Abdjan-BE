<?php

namespace App\Models;

use Database\Seeders\ProjectMaterialSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'customer_name',
        'project_name',
        'start_date',
        'end_date',
        'total'
    ];


    public function materials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class);
    }
}
