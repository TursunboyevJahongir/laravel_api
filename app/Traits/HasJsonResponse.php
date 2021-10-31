<?php

namespace App\Traits;

use App\Http\Resources\Api\PaginationResourceCollection;
use Illuminate\Http\JsonResponse;

trait HasJsonResponse
{
    /**
     * @param string $message
     * @param mixed|null $data
     * @param mixed|null $append
     * @param int $code
     * @return JsonResponse
     */
    protected function success(string $message = '', mixed $data = null, mixed $append = null, int $code = 200): JsonResponse
    {
        $resp = [
            'status' => true,
            'message' => $message,
            'data' => $data ?? new \stdClass(),
            'append' => $append ?? new \stdClass(),
            'errors' => []
        ];
        if ($data instanceof PaginationResourceCollection) {
            $data = $data->toResponse(null)->getData(true);
            unset($data['meta'], $data['links']);
            $resp = array_merge($resp, $data);
        }
        return response()->json($resp, $code);
    }

    /**
     * @param string $message
     * @param mixed|null $errors
     * @param int $code
     * @return JsonResponse
     */
    protected function error(string $message = '', mixed $errors = null, int $code = 404): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => new \stdClass(),
            'append' => new \stdClass(),
            'errors' => $errors ?? []
        ], $code);
    }
}
