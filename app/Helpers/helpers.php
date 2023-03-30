<?php

use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Determine if request url came from public user
 * @param string $key
 * @param string|null $fullString
 * @return bool
 */
function isPublicRoute(string $key = 'public', string $fullString = null): bool
{
    return Str::contains(request()->url(),'public');
}

/**
 * Determine if current url didn't come from public user
 * @param string $key
 * @param string|null $fullString
 * @return bool
 */
function isNotPublicRoute(string $key = 'public', string $fullString = null): bool
{
    return !Str::contains($fullString ?: request()->url(),$key ?: 'public');
}

function imageRules(bool $isUpdate , array $rules = null): array
{
        return $rules ?: [
            $isUpdate ? 'sometimes' : 'required',
            'image',
            'mimes:jpg,png,jpeg,jfif',
            'max:1000'
        ];
}


function passwordRules(bool $isUpdate , array $rules = null): array
{
    return $rules ?: [
        $isUpdate ? 'sometimes' : 'required',
        'confirmed' ,
        Password::min(6)
            ->mixedCase()
            ->numbers()
            ->symbols()
    ];
}
