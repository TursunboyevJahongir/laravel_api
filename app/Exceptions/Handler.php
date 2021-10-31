<?php

namespace App\Exceptions;

use App\Traits\HasJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HasJsonResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|Response
    {
        Log::error($e);
        if ($request->expectsJson()) {
            if ($e instanceof ValidationException && $request->wantsJson()) {
                $errors = $e->errors();
                $preparedErrors = [];
                foreach ($errors as $key => $value) {
                    $preparedErrors[] = [
                        'field' => $key,
                        'message' => $value[0] ?? ''
                    ];
                }
                return $this->error($e->getMessage(), $preparedErrors);
            }

            if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                return $this->error($e->getMessage());
            }

            return $this->error($e->getMessage());
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception): Response
    {
        return $request->expectsJson()
            ? response()->json(['status' => false, 'message' => $exception->getMessage(), 'data' => new \stdClass(), 'append' => new \stdClass()], 401)
            : parent::unauthenticated($request, $exception);
    }
}
