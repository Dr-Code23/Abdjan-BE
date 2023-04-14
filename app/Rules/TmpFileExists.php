<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TmpFileExists implements ValidationRule
{
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!file_exists(storage_path('app/public/tmp/' . date('Y_m_d_H' , strtotime('+ 3 hours')) ."/". $value))){

            $fail(translateErrorMessage('file' , 'not_found'));
        }
    }
}
