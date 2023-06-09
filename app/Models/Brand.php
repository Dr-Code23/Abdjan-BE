<?php

namespace App\Models;

use App\Http\Controllers\BrandController;
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
 * App\Models\Brand
 *
 * @property int $id
 * @property string $name
 * @method static Builder|Brand newModelQuery()
 * @method static Builder|Brand newQuery()
 * @method static Builder|Brand query()
 * @method static Builder|Brand whereId($value)
 * @method static Builder|Brand whereName($value)
 * @property array $status
 * @property string|null $img
 * @method static Builder|Brand whereImg($value)
 * @method static Builder|Brand whereStatus($value)
 * @property-read MediaCollection<int, Media> $image
 * @property-read int|null $image_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @mixin Eloquent
 */
class Brand extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    public $timestamps = false;
    public array $translatable = ['name'];
    protected $fillable = ['name' , 'status'];

    public function image(): MorphMany
    {
        return $this->media()
            ->where('collection_name', BrandController::$collectionName)
//            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
