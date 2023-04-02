<?php

namespace App\Models;

use App\Http\Controllers\AboutUsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\AboutUs
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $image
 * @property-read int|null $image_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs query()
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AboutUs whereName($value)
 * @mixin \Eloquent
 */
class AboutUs extends Model implements HasMedia
{
    use HasFactory , HasTranslations , InteractsWithMedia;

    public $timestamps = false;
    public array $translatable = [
        'name' ,
        'description'
    ];

    protected $fillable = [
        'name' ,
        'description'
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name' , AboutUsController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
