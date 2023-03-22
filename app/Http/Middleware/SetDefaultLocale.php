<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Locale');

        if($locale && in_array($locale , config('translatable.locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
