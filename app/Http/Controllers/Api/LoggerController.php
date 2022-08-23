<?php

namespace App\Http\Controllers\Api;

use App\Contracts\LoggerServiceContract;
use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
use App\Models\Logger;
use Illuminate\Http\JsonResponse;
use App\Core\Http\Controllers\CoreController as Controller;

class LoggerController extends Controller
{
    public function __construct(LoggerServiceContract $service)
    {
        parent::__construct($service);
    }

    public function index(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $loggers = $this->service->get($request);

        return $this->responseWith(['loggers' => $loggers]);
    }

    public function show(Logger $logger, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $logger = $this->service->show($logger, $request);

        return $this->responseWith(compact('logger'));
    }

    public function destroy(Logger $logger): JsonResponse
    {
        try {
            $this->service->delete($logger);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage(), logging: true);
        }
    }
}
