<?php

namespace App\Models;

use App\Http\Controllers\CategoryController;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $parent_id
 * @property array $name
 * @property int $status
 * @property string|null $img
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Category> $sub_categories
 * @property-read int|null $sub_categories_count
 * @property-read Collection<int, Category> $sub_sub_categories
 * @property-read int|null $sub_sub_categories_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereImg($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereStatus($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    public array $translatable = ['name'];
    protected $fillable = [
        'parent_id',
        'name',
        'status'
    ];

    public function images(): MorphMany
    {
        return $this->media()
            ->where('collection_name', CategoryController::$categoriesCollectionName)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }

    public function sub_sub_categories(): HasMany
    {
        return $this->sub_categories();
    }

    public function sub_categories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
