<?php

namespace App\Exceptions;

use App\Core\Helpers\ResponseCode;
use App\Core\Traits\Responsible as ResponsableTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TypeError;

class Handler extends ExceptionHandler
{
    use ResponsableTrait;

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
     */
    public function register()
    {
    }

    public function render($request, Throwable $e)
    {
        // Non standard errors handler
        if ($e instanceof ModelNotFoundException) {
            $message = $e->getMessage();
            if (str_contains($message, 'No query results for model')) {
                $message = __('errors.no_records');
            }

            return response()->json(['code'    => ResponseCode::HTTP_NOT_FOUND,
                                     'message' => $message,
                                     'data'    => []],
                                    ResponseCode::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotFoundHttpException) {//route not found
            return response()->json(['code'    => ResponseCode::HTTP_NOT_FOUND,
                                     'message' => __('errors.no_records'),
                                     'data'    => []],
                                    ResponseCode::HTTP_NOT_FOUND);
        }
        if ($e instanceof PermissionAlreadyExists) {
            return response()->json(['code'    => ResponseCode::HTTP_UNPROCESSABLE_ENTITY,
                                     'message' => 'Permission already exists for this guard',
                                     'data'    => []],
                                    ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($e instanceof AuthorizationException) {
            return response()->json(['code'    => ResponseCode::HTTP_FORBIDDEN,
                                     'message' => $e->getMessage(),
                                     'data'    => []],
                                    ResponseCode::HTTP_FORBIDDEN);
        }

        if ($e instanceof UnauthorizedException) {
            return response()->json(['code'    => ResponseCode::HTTP_FORBIDDEN,
                                     'message' => 'You dont have permissions to do this action',
                                     'data'    => []],
                                    ResponseCode::HTTP_FORBIDDEN);
        }
        if ($e instanceof RoleAlreadyExists) {
            return response()->json(['code'    => ResponseCode::HTTP_UNPROCESSABLE_ENTITY,
                                     'message' => 'This role already exists',
                                     'data'    => []],
                                    ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof TypeError) {
            return response()->json(['code'    => ResponseCode::HTTP_INTERNAL_SERVER_ERROR,
                                     'message' => $e->getMessage(),
                                     'data'    => []],
                                    ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json(['code'    => ResponseCode::HTTP_METHOD_NOT_ALLOWED,
                                     'message' => $e->getMessage(),
                                     'data'    => []],
                                    ResponseCode::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json(['code'    => ResponseCode::HTTP_UNAUTHORIZED,
                                     'message' => $e->getMessage(),
                                     'data'    => []],
                                    ResponseCode::HTTP_UNAUTHORIZED);
        }
        // End

        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($this->mapException($e));

        foreach ($this->renderCallbacks as $renderCallback) {
            foreach ($this->firstClosureParameterTypes($renderCallback) as $type) {
                if (is_a($e, $type)) {
                    $response = $renderCallback($e, $request);

                    if (!is_null($response)) {
                        return $response;
                    }
                }
            }
        }

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }
        if ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }

        if ($e instanceof \Exception) {
            return response()->json(['code'    => ResponseCode::HTTP_INTERNAL_SERVER_ERROR,
                                     'message' => $e->getMessage(),
                                     'data'    => $e->getTrace()],
                                    ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->shouldReturnJson($request, $e)
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
    }

    /**
     * Convert a validation exception into a JSON response.
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return $this->responseWith(['errors' => $exception->errors()], $exception->status, $exception->getMessage());
    }
}
