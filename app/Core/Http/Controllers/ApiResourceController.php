<?php

namespace App\Core\Http\Controllers;

use App\Core\Services\CoreService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiResourceController extends CoreController
{
    protected string|null    $model         = null;
    protected string         $requestParam;
    private FormRequest|null $createRequest = null;
    private FormRequest|null $updateRequest = null;
    private string           $index;
    private string           $show;

    public function __construct(
        CoreService $service,
        FormRequest|null $createRequest = null,
        FormRequest|null $updateRequest = null,
        string|bool $checkPermission = true,
    ) {
        try {
            parent::__construct($service);
            $this->constructFormatter($checkPermission, $createRequest, $updateRequest);
        } catch (Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }

    private function constructFormatter($checkPermission, $createRequest, $updateRequest)
    {
        [$checkPermission, $route, $this->index, $this->show, $this->model] = cache()
            ->remember(class_basename(static::class) . '_cache', 604800,//week
                function () use ($checkPermission, $createRequest, $updateRequest) {
                    if (!$this->model) {
                        $namePart    = explode('Controller', class_basename(static::class))[0];
                        $this->model = 'App\Models\\' . $namePart;
                        if (!class_exists($this->model)) {
                            $this->model = 'App\Core\Models\\' . $namePart;
                        }
                    }
                    $category = Str::snake(Str::singular(
                        str_replace('Controller', '', class_basename(static::class)
                        )));
                    $name     = strtolower(class_basename($this->model));


                    $this->requestParam = request()->route()?->parameterNames[0] ?? $name;
                    [$route] = explode('.', \Route::currentRouteName() ?? '');

                    if ($checkPermission) {
                        $checkPermission = is_bool($checkPermission) ? $category : $checkPermission;
                    }

                    $index = Str::plural(Str::camel(class_basename($this->model)));
                    $show  = Str::singular(Str::camel(class_basename($this->model)));

                    return [$checkPermission, $route, $index, $show, $this->model];
                });

        if ($checkPermission) {
            $this->middleware("permission:read-{$checkPermission}")->only(['index', 'show']);
            $this->middleware("permission:create-{$checkPermission}")->only(['store']);
            $this->middleware("permission:update-{$checkPermission}")->only(['update']);
            $this->middleware("permission:delete-{$checkPermission}")->only(['destroy']);
        }

        if (\Route::has("$route.store")) {
            $this->createRequest = $createRequest ??
                (new (config('modulegenerator.web.request_path') . '\\' . class_basename($this->model) . "CreateRequest")());
        }
        if (\Route::has("$route.update")) {
            $this->updateRequest = $updateRequest ??
                (new (config('modulegenerator.web.request_path') . '\\' . class_basename($this->model) . "UpdateRequest")());
        }
    }

    public function index(): JsonResponse
    {
        try {
            $result = $this->service->index();

            return $this->responseWith([$this->index => $result]);
        } catch (Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }

    public function show(): JsonResponse
    {
        try {
            $result = $this->service->show((int)request()->route($this->requestParam));

            return $this->responseWith([$this->show => $result]);
        } catch (ModelNotFoundException $e) {
            return $this->responseWith(code: 404, message: __('messages.entity_not_found_exception'));
        } catch (Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }

    public function store(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), $this->createRequest->rules());
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $result = $this->service->create($validator);

            return $this->responseWith([$this->show => $result], 201);
        } catch (ValidationException $e) {
            return $this->responseWith(['errors' => $e->errors()], 422, __('messages.validation_exception'));
        } catch (\Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }

    public function update(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), $this->updateRequest->rules());
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $this->service->update((int)request()->route($this->requestParam), $validator);

            return $this->responseWith(code: 204);
        } catch (ModelNotFoundException $e) {
            return $this->responseWith(code: 404, message: __('messages.entity_not_found_exception'));
        } catch (ValidationException $e) {
            return $this->responseWith(['errors' => $e->errors()], 422, __('messages.validation_exception'));
        } catch (\Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }

    public function destroy(): JsonResponse
    {
        try {
            $this->service->delete((int)request()->route($this->requestParam));

            return $this->responseWith(code: 204);
        } catch (ModelNotFoundException $e) {
            return $this->responseWith(code: 404, message: __('messages.entity_not_found_exception'));
        } catch (\Exception $e) {
            return $this->responseWith(['trace' => $e->getTrace()], $e->getCode(), $e->getMessage());
        }
    }
}
