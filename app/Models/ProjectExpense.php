<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Support\Carbon;

class ProjectExpense extends Model
{
    use HasFactory;

    protected $fillable = ['project_id'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function project_expense_product(): HasMany
    {
        return $this->hasMany(ProjectExpenseProduct::class);
    }

    public function createdAt(): Manipulator{
        return Manipulator::get(fn($val) => (new Carbon($val))->format('Y-m-d h:i'));
    }
}
