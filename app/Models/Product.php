<?php

namespace App\Models;

use App\Models\Translations\ProductTranslation;
use App\Traits\DateTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use \Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model implements TranslatableContract
{
    use HasFactory , Translatable , DateTrait;
    public array $translatedAttributes = [
        'title',
        'description'
    ];
    protected $fillable = [
        'quantity',
        'unit_price',
        'unit_id',
        'brand_id',
        'category_id',
        'attribute_id'
    ];

    public function translation(): HasOne
    {
        return $this->hasOne(
            ProductTranslation::class
        )->withDefault(['title' => null , 'description' => null]);
    }

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
}
