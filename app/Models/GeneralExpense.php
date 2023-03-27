<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute as Manipulator;
use Illuminate\Support\Carbon;

class GeneralExpense extends Model
{
    use HasFactory;

    protected $fillable = ['price' , 'reason'];

    public function createdAt(): Manipulator{
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }
    public function updatedAt(): Manipulator{
        return Manipulator::get(
            fn($val) => (new Carbon($val))->format('Y-m-d H:i')
        );
    }
}
