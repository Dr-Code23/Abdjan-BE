<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property-read Product $product
 * @method static Builder|ProjectExpenseProduct newModelQuery()
 * @method static Builder|ProjectExpenseProduct newQuery()
 * @method static Builder|ProjectExpenseProduct query()
 * @method static Builder|ProjectExpenseProduct whereId($value)
 * @method static Builder|ProjectExpenseProduct wherePricePerUnit($value)
 * @method static Builder|ProjectExpenseProduct whereProductId($value)
 * @method static Builder|ProjectExpenseProduct whereProjectExpenseId($value)
 * @method static Builder|ProjectExpenseProduct whereQuantity($value)
 * @mixin Eloquent
 */
class ProjectExpenseProduct extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
