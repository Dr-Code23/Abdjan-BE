<?php

use Illuminate\Support\Str;

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
    return !Str::contains(request()->url(),'public');
}
