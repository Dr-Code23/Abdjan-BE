<?php

namespace App\Models;

use App\Http\Controllers\SettingController;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $address
 * @property array $name
 * @property string $social_links
 * @property string $phones
 * @property-read MediaCollection<int, Media> $logo
 * @property-read int|null $logo_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereAddress($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereName($value)
 * @method static Builder|Setting wherePhones($value)
 * @method static Builder|Setting whereSocialLinks($value)
 * @mixin Eloquent
 */
class Setting extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public $timestamps = false;
    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'phones',
        'facebook',
        'whatsapp',
        'instagram',
        'youtube',
        'address',
        'email'
    ];
    use HasFactory;

//    public function phones(): Manipulator{
//
//        return Manipulator::make(
//            get: fn($val) => json_decode($val),
//            set: fn($val) => json_encode($val)
//        );
//    }

    public function logo(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', SettingController::$collectionName)
            ->take(1)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
