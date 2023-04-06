<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ContactUs
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ContactUs newModelQuery()
 * @method static Builder|ContactUs newQuery()
 * @method static Builder|ContactUs query()
 * @method static Builder|ContactUs whereCreatedAt($value)
 * @method static Builder|ContactUs whereEmail($value)
 * @method static Builder|ContactUs whereId($value)
 * @method static Builder|ContactUs whereMessage($value)
 * @method static Builder|ContactUs whereName($value)
 * @method static Builder|ContactUs wherePhone($value)
 * @method static Builder|ContactUs whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ContactUs extends Model
{
    use HasFactory;
}
