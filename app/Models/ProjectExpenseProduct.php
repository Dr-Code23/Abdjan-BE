<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProjectExpenseProduct
 *
 * @property int $id
 * @property int $project_expense_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price_per_unit
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct wherePricePerUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct whereProjectExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectExpenseProduct whereQuantity($value)
 * @mixin \Eloquent
 */
class ProjectExpenseProduct extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
