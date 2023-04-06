<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProjectExpense
 *
 * @property int $id
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Project $project
 * @property-read Collection<int, ProjectExpenseProduct> $project_expense_product
 * @property-read int|null $project_expense_product_count
 * @method static Builder|ProjectExpense newModelQuery()
 * @method static Builder|ProjectExpense newQuery()
 * @method static Builder|ProjectExpense query()
 * @method static Builder|ProjectExpense whereCreatedAt($value)
 * @method static Builder|ProjectExpense whereId($value)
 * @method static Builder|ProjectExpense whereProjectId($value)
 * @method static Builder|ProjectExpense whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProjectExpense extends Model
{
    use HasFactory;

    protected $fillable = ['project_id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function project_expense_product(): HasMany
    {

        return $this->hasMany(ProjectExpenseProduct::class);
    }

    public function createdAt(): Manipulator
    {
        return Manipulator::get(fn($val) => (new Carbon($val))->format('Y-m-d h:i'));
    }
}
