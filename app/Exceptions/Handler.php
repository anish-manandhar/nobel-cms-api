<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $exception) {

            if ($exception instanceof NotFoundHttpException) {
                if ($exception->getPrevious() && $exception->getPrevious() instanceof ModelNotFoundException) {
                    $model = $exception->getPrevious()->getModel();
                    $model_name = class_basename($model);
                    return response()->json([
                        'message' => "{$model_name} not found",
                    ], 404);
                }
            }
        });

        $this->renderable(function (Exception $exception, $request) {
            if ($exception instanceof UnauthorizedException)
                return response(['message' => 'You do not have required permission.',], 403);
        });
    }
}
