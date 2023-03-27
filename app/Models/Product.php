<?php

namespace App\Models;

use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory , HasTranslations , DateTrait;
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

    public function optionalImages(): Manipulator
    {
        return Manipulator::make(
            get: fn($val) => json_decode($val , true),
            set: fn($val) => json_encode($val),
        );
    }

    public function project_expenses_products(){
        return $this->hasMany(ProjectExpenseProduct::class);
    }
}
