<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileExistsRule implements ValidationRule
{
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!file_exists(storage_path('app/public/tmp/' . date('Y_m_d_H') ."/". $value))){

            $fail(translateErrorMessage('file' , 'not_found'));
        }
    }
}
