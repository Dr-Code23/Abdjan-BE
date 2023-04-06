<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MeasureUnit
 *
 * @property int $id
 * @property string $name
 * @method static Builder|MeasureUnit newModelQuery()
 * @method static Builder|MeasureUnit newQuery()
 * @method static Builder|MeasureUnit query()
 * @method static Builder|MeasureUnit whereId($value)
 * @method static Builder|MeasureUnit whereName($value)
 * @mixin Eloquent
 */
class MeasureUnit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name'];
    protected $table = 'measurement_units';
}
