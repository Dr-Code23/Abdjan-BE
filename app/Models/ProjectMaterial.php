<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProjectMaterial
 *
 * @property int $id
 * @property int $project_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price_per_unit
 * @property-read Product $product
 * @method static Builder|ProjectMaterial newModelQuery()
 * @method static Builder|ProjectMaterial newQuery()
 * @method static Builder|ProjectMaterial query()
 * @method static Builder|ProjectMaterial whereId($value)
 * @method static Builder|ProjectMaterial wherePricePerUnit($value)
 * @method static Builder|ProjectMaterial whereProductId($value)
 * @method static Builder|ProjectMaterial whereProjectId($value)
 * @method static Builder|ProjectMaterial whereQuantity($value)
 * @mixin Eloquent
 */
class ProjectMaterial extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'project_id',
        'product_id',
        'quantity',
        'price_per_unit'
    ];
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
