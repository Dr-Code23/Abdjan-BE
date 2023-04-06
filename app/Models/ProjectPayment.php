<?php

namespace App\Models;

use App\Traits\DateTrait;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProjectPayment
 *
 * @property int $id
 * @property int $project_id
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Project $project
 * @method static Builder|ProjectPayment newModelQuery()
 * @method static Builder|ProjectPayment newQuery()
 * @method static Builder|ProjectPayment query()
 * @method static Builder|ProjectPayment whereCreatedAt($value)
 * @method static Builder|ProjectPayment whereId($value)
 * @method static Builder|ProjectPayment wherePrice($value)
 * @method static Builder|ProjectPayment whereProjectId($value)
 * @method static Builder|ProjectPayment whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProjectPayment extends Model
{
    use HasFactory, DateTrait;

    protected $fillable = ['project_id', 'price'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdAt(): Manipulator
    {
        return Manipulator::get(fn($val) => (new Carbon($val))->format('Y-m-d H:i'));
    }
}
