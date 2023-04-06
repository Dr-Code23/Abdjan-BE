<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\GeneralExpense
 *
 * @property int $id
 * @property float $price
 * @property string $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|GeneralExpense newModelQuery()
 * @method static Builder|GeneralExpense newQuery()
 * @method static Builder|GeneralExpense query()
 * @method static Builder|GeneralExpense whereCreatedAt($value)
 * @method static Builder|GeneralExpense whereId($value)
 * @method static Builder|GeneralExpense wherePrice($value)
 * @method static Builder|GeneralExpense whereReason($value)
 * @method static Builder|GeneralExpense whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GeneralExpense extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'reason'];

    public function createdAt(): Manipulator
    {
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }

    public function updatedAt(): Manipulator
    {
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }
}
