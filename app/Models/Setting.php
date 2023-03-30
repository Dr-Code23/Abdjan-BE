<?php

namespace App\Models;

use App\Http\Controllers\SettingController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Setting extends Model implements HasMedia
{
    use HasTranslations , InteractsWithMedia;
    public $timestamps = false;
    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'phones',
        'social_links',
        'address'
    ];
    use HasFactory;

    public function phones(): Manipulator{

        return Manipulator::make(
            get: fn($val) => json_decode($val),
            set: fn($val) => json_encode($val)
        );
    }
    public function socialLinks(): Manipulator{

        return Manipulator::make(
            get: fn($val) => json_decode($val),
            set: fn($val) => json_encode($val)
        );
    }

    public function logo(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name' , SettingController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
