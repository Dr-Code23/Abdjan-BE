<?php

namespace App\Models;

use App\Http\Controllers\AboutUsController;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\AboutUs
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property-read MediaCollection<int, Media> $image
 * @property-read int|null $image_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @method static Builder|AboutUs newModelQuery()
 * @method static Builder|AboutUs newQuery()
 * @method static Builder|AboutUs query()
 * @method static Builder|AboutUs whereDescription($value)
 * @method static Builder|AboutUs whereId($value)
 * @method static Builder|AboutUs whereName($value)
 * @mixin Eloquent
 */
class AboutUs extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    public $timestamps = false;
    public array $translatable = [
        'name',
        'description'
    ];

    protected $fillable = [
        'name',
        'description'
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', AboutUsController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
