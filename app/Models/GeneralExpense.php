<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Support\Carbon;

/**
 * App\Models\GeneralExpense
 *
 * @property int $id
 * @property float $price
 * @property string $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GeneralExpense whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GeneralExpense extends Model
{
    use HasFactory;

    protected $fillable = ['price' , 'reason'];

    public function createdAt(): Manipulator{
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }
    public function updatedAt(): Manipulator{
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }
}
