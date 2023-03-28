<?php

namespace App\Models;

use App\Http\Controllers\CategoryController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory, HasTranslations , InteractsWithMedia;

    public array $translatable = ['name'];
    protected $fillable = [
        'parent_id' ,
        'name',
        'status'
    ];

    public function sub_categories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function images(): MorphMany
    {
        return $this->media()
            ->where('collection_name' , CategoryController::$categoriesCollectionName)
            ->select(['id' , 'model_id', 'disk' , 'file_name'])
            ->limit(1);
    }
}
