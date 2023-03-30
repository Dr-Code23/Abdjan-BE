<?php

namespace App\Models;

use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use  \Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProjectPayment
 *
 * @property int $id
 * @property int $project_id
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectPayment extends Model
{
    use HasFactory , DateTrait;

    protected $fillable = ['project_id' , 'price'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdAt():Manipulator{
        return Manipulator::get(fn($val) => (new Carbon($val))->format('Y-m-d H:i'));
    }
}
