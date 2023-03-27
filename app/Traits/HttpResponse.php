<?php

namespace App\Traits;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait HttpResponse
{
    /**
     * Success Response.
     */
    public function successResponse(
        mixed  $data = null,
        string $msg = 'Success',
        int    $code = Response::HTTP_OK
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'type' => 'success',
            'code' => $code,
        ], $code);
    }

    /**
     * Response With Cookie.
     *
     * @param mixed $cookie
     * @param mixed|null $data
     * @param string $msg
     * @param string $type
     * @param int $code
     * @return JsonResponse
     */
    public function responseWithCookie(
        mixed  $cookie,
        mixed  $data = null,
        string $msg = 'msg',
        string $type = 'success',
        int    $code = 200
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'type' => $type,
            'code' => $code,
        ], $code)->withCookie($cookie);
    }

    public function unauthenticatedResponse(
        string $msg = 'You Are not authenticated',
        int    $code = Response::HTTP_UNAUTHORIZED,
               $data = null
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'type' => 'error',
            'code' => $code,
        ], $code);
    }

    /**
     *  NotAuthenticated Response In Handler.
     *
     * @return void
     *
     * @throws AuthenticationException
     */
    public function throwNotAuthenticated(): void
    {
        throw new AuthenticationException();
    }

    /**
     * Undocumented function
     *
     * @param $data
     * @param string $msg
     * @param integer $code
     * @return JsonResponse
     */
    public function resourceResponse(
        $data,
        string $msg = 'Data Fetched Successfully',
        int $code = 200
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'code' => $code,
            'type' => 'success',
        ], $code);
    }

    /**
     * Return Forbidden Response
     *
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return JsonResponse
     */
    public function forbiddenResponse(
        string $msg = 'You do not have permissions to access this resource',
        mixed  $data = null,
        int    $code = Response::HTTP_FORBIDDEN
    ): JsonResponse
    {
        return $this->error($data, $code, $msg);
    }

    /**
     * Error Response
     *
     * @param $data
     * @param int $code
     * @param string $msg
     * @return JsonResponse
     */
    public function error(
        $data = null,
        int $code = Response::HTTP_NOT_FOUND,
        string $msg = 'Error Occurred'
    ): JsonResponse
    {
        return response()->json(
            [
                'data' => $data,
                'msg' => $msg,
                'type' => 'error',
                'code' => $code,
            ],
            $code
        )
            ->withHeaders(['Accept' => 'application/json']);
    }

    public function noContentResponse(): \Illuminate\Http\Response
    {
        return response()->noContent();
    }

    public function notFoundResponse(
        string     $msg = 'Not Found',
        array|null $data = null,
        int        $code = Response::HTTP_NOT_FOUND
    ): JsonResponse
    {
        return $this->error($data, $code, $msg);
    }

    public function createdResponse(
        array|null|JsonResource $data = null,
        string                  $msg = 'Resource Created Successfully',
        int                     $code = Response::HTTP_CREATED
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'code' => $code,
            'type' => 'success',
        ], $code);
    }

    /**
     * @throws ValidationException
     */
    public function throwValidationException(
        Validator $validator,
        array $errors = null
    ): void
    {
        $errors = $errors ?: $validator->errors()->toArray();

        foreach (array_keys($errors) as $key) {
            $errors[$key] = $errors[$key][0];
        }

        throw new ValidationException($validator, $this->validationErrorsResponse($errors));
    }

    /**
     * Validation Errors Response.
     *
     * @param mixed|null $data
     */
    public function validationErrorsResponse(
        mixed  $data = null,
        int    $code = Response::HTTP_UNPROCESSABLE_ENTITY,
        string $msg = 'validation errors'
    ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'msg' => $msg,
            'type' => 'error',
            'code' => $code,
        ], $code);
    }
}
