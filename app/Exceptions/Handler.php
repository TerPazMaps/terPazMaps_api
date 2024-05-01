<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return $this->renderUnauthorizedJsonResponse($exception);
        }

        return parent::render($request, $exception);
    }

    protected function renderUnauthorizedJsonResponse(UnauthorizedHttpException $exception)
    {
        return response()->json([
            'error' => 'Unauthorized',
            'message' => $exception->getMessage()
        ], 401);
    }
}
