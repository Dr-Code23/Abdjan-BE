<?php

namespace App\Models;

use App\Http\Controllers\AdController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Ad extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'discount'
    ];

    protected array $translatable = [
        'title',
        'description'
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name' , AdController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
