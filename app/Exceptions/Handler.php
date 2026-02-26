<?php

namespace App\Exceptions;

use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

final class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        LazyLoadingViolationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    #[\Override]
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenExpiredException) {
            return response()->json([
                'ok'    => false,
                'error' => 'token_expired'
            ], 401);
        }

        if ($e instanceof TokenInvalidException) {
            return response()->json([
                'ok'    => false,
                'error' => 'token_invalid'
            ], 401);
        }

        if ($e instanceof JWTException) {
            return response()->json([
                'ok'    => false,
                'error' => 'token_absent'
            ], 401);
        }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthorized'], 401)
            : redirect()->guest(route('login'));
    }
}
