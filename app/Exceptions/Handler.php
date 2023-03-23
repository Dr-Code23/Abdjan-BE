<?php

namespace App\Exceptions;

use App\Traits\HttpResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HttpResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle Unauthorized User
        $this->renderable(function (AuthenticationException $e, $req) {

            if ($req->is('api/*')) {

                return $this->unauthenticatedResponse('You are not authenticated');
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $req) {
            if ($req->is('api/*')) {
                $msg = $e->getMessage();

                // Handle Model Not Found In Query Binding

//                if(Str::contains($msg , 'No query' , true)){
//                    $msg = translateErrorMessage('record' , 'not_found');
//                }

                return $this->error(null, Response::HTTP_NOT_FOUND, $msg);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {

                return $this->error(null, Response::HTTP_METHOD_NOT_ALLOWED, $e->getMessage());
            }
        });

        // Too Many Requests
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            if ($request->is('api/*')) {

                return $this->error(null, Response::HTTP_TOO_MANY_REQUESTS, $e->getMessage());
            }
        });
    }
}
