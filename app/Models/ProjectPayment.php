<?php

namespace App\Models;

use App\Traits\DateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use  \Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Support\Carbon;

class ProjectPayment extends Model
{
    use HasFactory , DateTrait;

    protected $fillable = ['project_id' , 'price'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdAt():Manipulator{
        return Manipulator::get(fn($val) => (new Carbon($val))->format('Y-m-d H:i'));
    }
}
