<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\ProjectMaterial
 *
 * @property int $id
 * @property int $project_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price_per_unit
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial wherePricePerUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMaterial whereQuantity($value)
 * @mixin \Eloquent
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
