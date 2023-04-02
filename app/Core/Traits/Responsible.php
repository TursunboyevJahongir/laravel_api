<?php


namespace App\Core\Traits;

use App\Core\Helpers\ResponseCode;
use App\Events\LoggerEvent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

use function response;

trait Responsible
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

    public function noContent(): JsonResponse
    {
        return $this->responseWith(code: 204);
    }

    public function errorException(Exception $e): JsonResponse
    {
        if (is_string($e->getCode())) {
            dd(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }

        return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
    }

    public function responseWith(
            array|Collection $data = [],
            //https://blog.jetbrains.com/phpstorm/2020/10/phpstorm-2020-3-eap-4/
            //https://habr.com/ru/company/JetBrains/blog/531828/
            #[\JetBrains\PhpStorm\ExpectedValues(valuesFromClass: ResponseCode::class)]
            int $code = ResponseCode::HTTP_OK,
            string $message = '',
            bool $logging = false
    ): JsonResponse {
        $this->message    = empty($message) ? __("statuscode.$code") : $message;
        $this->data       = $data;
        $this->statusCode = match ($code) {
            ResponseCode::HTTP_OK => 200,
            ResponseCode::HTTP_NO_CONTENT => 204,
            ResponseCode::HTTP_CREATED => 201,
            ResponseCode::HTTP_BAD_REQUEST => 400,
            ResponseCode::HTTP_UNAUTHORIZED => 401,
            ResponseCode::HTTP_FORBIDDEN => 403,
            ResponseCode::HTTP_METHOD_NOT_ALLOWED => 405,
            ResponseCode::HTTP_NOT_FOUND => 404,
            ResponseCode::HTTP_UNPROCESSABLE_ENTITY => 422,
            ResponseCode::HTTP_BAD_GATEWAY => 502,
            ResponseCode::HTTP_GATEWAY_TIMEOUT => 504,
            ResponseCode::HTTP_INTERNAL_SERVER_ERROR, 0 => 500,
        };

        if ($logging) {
            event(new LoggerEvent(response: $this->data, response_status: $this->statusCode,
                    response_message:       $this->message));
        }

        return $this->response();
    }

    /**
     * Send response
     */
    private function response(): JsonResponse
    {
        $data = ['code' => $this->statusCode, 'message' => $this->message, 'data' => $this->data];

        return response()->json($data, $this->statusCode);
    }

}
