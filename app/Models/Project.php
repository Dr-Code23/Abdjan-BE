<?php

namespace App\Models;

use App\Traits\DateTrait;
use Database\Seeders\ProjectMaterialSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $customer_name
 * @property string $project_name
 * @property float $total for materials
 * @property float $project_total for all project
 * @property string $start_date
 * @property string $end_date
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectMaterial> $materials
 * @property-read int|null $materials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectExpense> $project_expenses
 * @property-read int|null $project_expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectPayment> $project_payments
 * @property-read int|null $project_payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTotal($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectMaterial> $materials
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectExpense> $project_expenses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectPayment> $project_payments
 * @mixin \Eloquent
 */
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
