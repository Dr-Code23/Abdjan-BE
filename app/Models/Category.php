<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

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
}
