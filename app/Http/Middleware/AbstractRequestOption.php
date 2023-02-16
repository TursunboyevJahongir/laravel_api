<?php

namespace App\RequestOptionServices\AbstractRequestOption;

use Carbon\Carbon;
use GuzzleHttp\Promise as P;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

abstract class AbstractRequestOption
{
    protected Carbon         $startDateTime;
    protected ?TransferStats $logStats = null;

    /**
     * Middleware that logs requests, responses, and errors using a message formatter.
     */
    public function __construct()
    {
        $this->startDateTime = Carbon::now()->timezone(config('app.timezone'));
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            if (isset($options['on_stats'])) {
                $options['on_stats'] = function (TransferStats $stats) {
                    $this->logStats = $stats;
                };
            }

            return $handler($request, $options)
                ->then(
                    $this->handleSuccess($request, $options),
                    $this->handleFailure($request, $options)
                );
        };
    }

    private function handleSuccess(
        RequestInterface $request,
        array $options = []
    ): callable {
        return function (ResponseInterface $response) use ($request, $options) {
            $this->level = 'info';
            $this->getRequestOptions($request, $response);

            return $response;
        };
    }

    private function handleFailure(
        RequestInterface $request,
        array $options = []
    ): callable {
        return function (\Exception $reason) use ($request, $options) {
            $response    = $reason instanceof RequestException ? $reason->getResponse() : null;
            $this->level = 'error';
            $this->getRequestOptions($request, $response, $reason);

            return P\Create::rejectionFor($reason);
        };
    }

    protected abstract function getRequestOptions(
        RequestInterface $request,
        ResponseInterface $response,
        RequestException|ConnectException $reason = null
    ): void;
}
