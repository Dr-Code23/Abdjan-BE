<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;
    public $timestamps = false;
    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'phones',
        'social_links'
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
}
