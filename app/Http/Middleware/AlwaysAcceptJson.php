<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlwaysAcceptJson
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only Accept Json From `routes/api.php`
        if($request->is('api/*')){
            $request->headers->set('Accept' , 'application/vnd.api+json');
        }
        return $next($request);
    }
}
