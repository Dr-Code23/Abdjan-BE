<?php

namespace App\Models;

use App\Http\Controllers\AboutUsController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class AboutUs extends Model implements HasMedia
{
    use HasFactory , HasTranslations , InteractsWithMedia;

    public $timestamps = false;
    public array $translatable = [
        'name' ,
        'description'
    ];

    protected $fillable = [
        'name' ,
        'description'
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name' , AboutUsController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
