<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return $this->handleAPIError($request, $e);
            }
        });
    }

    private function handleAPIError($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['success' => false, 'message' => 'Route not found !'], 404);
        } else if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 405);
        } else if ($exception instanceof AuthenticationException) {
            return redirect(route('api.unauthorized'));
        }
        $response = env('APP_ENV') == 'local' ? ['error' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine(), 'full' => $exception] : [];
        return response()->json(array_merge(['success' => false, 'message' => 'Terjadi Kesalahan pada Sistem !'], $response), 500);
    }
}
