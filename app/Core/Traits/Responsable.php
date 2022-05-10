<?php


namespace App\Core\Traits;

use App\Events\LoggerEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

use function response;

trait Responsable
{

    /**
     * Response message
     *
     * @var string
     */
    private $message = '';
    /**
     * Response data
     *
     * @var array|Collection
     */
    private array|Collection $data = [];
    /**
     * Response status code
     *
     * @var int
     */
    private int $statusCode;

    public function responseWith(
        array|Collection $data = [],
        int $code = 200,
        string $message = '',
        bool $logging = false
    ): JsonResponse {
        $this->message    = empty($message) ? Response::$statusTexts[$code] : $message;
        $this->data       = $data;
        $this->statusCode = match ($code) {
            200 => 200,
            204 => 204,
            201 => 201,
            401 => 401,
            403 => 403,
            404 => 404,
            422 => 422,
            500, 0 => 500,
        };

        if ($logging) {
            event(new LoggerEvent(response: $this->data, response_status: $this->statusCode,
                response_message:           $this->message));
        }

        return $this->response();
    }

    /**
     * Send response
     *
     * @return JsonResponse
     */
    private function response(): JsonResponse
    {
        $data = ['code' => $this->statusCode, 'message' => $this->message, 'data' => $this->data];

        return response()->json($data, $this->statusCode);
    }

}
