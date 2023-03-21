<?php

namespace App\Models;

use App\Models\Translations\ServiceTranslation;
use App\Traits\DateTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model implements TranslatableContract
{
    use HasFactory, Translatable, DateTrait;

    protected $fillable = [
        'category_id',
        'price',
        'phone'
    ];
    public array $translatedAttributes = [
        'name',
        'description'
    ];

    public function translation(): HasOne
    {
        return $this->hasOne(
            ServiceTranslation::class
        )->withDefault(['title' => null , 'description' => null]);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
