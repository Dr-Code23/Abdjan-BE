<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MeasureUnit
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|MeasureUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasureUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasureUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeasureUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeasureUnit whereName($value)
 * @mixin \Eloquent
 */
class MeasureUnit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name'];
    protected $table = 'measurement_units';
}
