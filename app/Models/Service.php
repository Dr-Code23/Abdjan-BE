<?php

namespace App\Models;

use App\Http\Controllers\ServiceController;
use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory, HasTranslations, DateTrait , InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'price',
        'phone',
        'status',
    ];
    public array $translatable = [
        'name',
        'description'
    ];

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

    public function images(): MorphMany
    {
        return $this->media()
            ->where('collection_name' , ServiceController::$serviceCollectionName)
            ->select(['id' , 'model_id', 'disk' , 'file_name']);
    }
}
