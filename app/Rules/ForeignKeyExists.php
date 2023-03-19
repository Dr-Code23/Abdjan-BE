<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ForeignKeyExists implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public bool $implicit = true;

    public function __construct(
        private readonly string $table,
        private readonly string $column = 'id',
        private readonly string $translateKey = ""
    ){}


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::selectOne(
            "select `id` from `{$this->table}` where `{$this->column}` = ?" ,
            [$value]
        );

        if(!$exists){
            $key = $this->translateKey ?: rtrim($this->table , 's');

            $fail(translateErrorMessage(
                $key,
                'not_found'
            ));
        }
    }
}
