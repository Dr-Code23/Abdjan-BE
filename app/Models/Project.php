<?php

namespace App\Models;

use App\Traits\DateTrait;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property-read Collection<int, ProjectMaterial> $materials
 * @property-read int|null $materials_count
 * @property-read Collection<int, ProjectExpense> $project_expenses
 * @property-read int|null $project_expenses_count
 * @property-read Collection<int, ProjectPayment> $project_payments
 * @property-read int|null $project_payments_count
 * @method static Builder|Project newModelQuery()
 * @method static Builder|Project newQuery()
 * @method static Builder|Project query()
 * @method static Builder|Project whereCustomerName($value)
 * @method static Builder|Project whereEndDate($value)
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereProjectName($value)
 * @method static Builder|Project whereProjectTotal($value)
 * @method static Builder|Project whereStartDate($value)
 * @method static Builder|Project whereTotal($value)
 * @mixin Eloquent
 */
class Project extends Model
{
    use HasFactory, DateTrait;

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
