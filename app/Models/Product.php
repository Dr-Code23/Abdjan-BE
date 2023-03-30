<?php

namespace App\Models;

use App\Traits\DateTrait;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property int $category_id
 * @property int $brand_id
 * @property int $attribute_id
 * @property int $unit_id
 * @property int $status
 * @property float $unit_price
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Attribute $attribute
 * @property-read Brand $brand
 * @property-read Category $category
 * @property-read MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, ProjectExpenseProduct> $project_expenses_products
 * @property-read int|null $project_expenses_products_count
 * @property-read MeasureUnit $unit
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereAttributeId($value)
 * @method static Builder|Product whereBrandId($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereQuantity($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Product whereUnitId($value)
 * @method static Builder|Product whereUnitPrice($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Product extends Model implements HasMedia
{
    use HasFactory,
        HasTranslations,
        DateTrait,
        InteractsWithMedia;

    public array $translatable = [
        'name',
        'description'
    ];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'unit_price',
        'unit_id',
        'brand_id',
        'category_id',
        'attribute_id'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasureUnit::class);
    }

    public function createdAt(): Manipulator
    {
        return Manipulator::get(
            get: fn($val) => $this->changeDateFormat($val, 'Y-m-d')
        );
    }

    public function updatedAt(): Manipulator
    {
        return Manipulator::get(
            get: fn($val) => $this->changeDateFormat($val, 'Y-m-d')
        );
    }

    public function project_expenses_products(): HasMany
    {
        return $this->hasMany(ProjectExpenseProduct::class);
    }

    public function images(): MorphMany
    {
        return $this->media()
            ->where('collection_name', 'products')
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
