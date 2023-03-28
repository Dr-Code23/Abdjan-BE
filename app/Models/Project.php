<?php

namespace App\Models;

use App\Traits\DateTrait;
use Database\Seeders\ProjectMaterialSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory , DateTrait;

    public $timestamps = false;
    protected $fillable = [
        'customer_name',
        'project_name',
        'start_date',
        'end_date',
        'total',
        'project_total'
    ];


    public function materials(): HasMany
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    public function project_payments(): HasMany
    {
        return $this->hasMany(ProjectPayment::class);
    }

    public function project_expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }
}
