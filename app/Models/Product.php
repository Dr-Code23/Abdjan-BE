<?php

namespace App\Models;

use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Product extends Model implements HasMedia
{
    use HasFactory ,
        HasTranslations ,
        DateTrait ,
        InteractsWithMedia;

    public array $translatable = [
        'name',
        'description'
    ];
    protected $fillable = [
        'name',
        'description',
        'main_image',
        'optional_images',
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
            get: fn($val) => $this->changeDateFormat($val , 'Y-m-d')
        );
    }

    public function updatedAt(): Manipulator
    {
        return Manipulator::get(
            get: fn($val) => $this->changeDateFormat($val , 'Y-m-d')
        );
    }

    public function project_expenses_products(): HasMany
    {
        return $this->hasMany(ProjectExpenseProduct::class);
    }
}
