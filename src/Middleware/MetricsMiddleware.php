<?php


namespace App\Middleware;


use Prometheus\RegistryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class MetricsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RegistryInterface $registry,
        private LoggerInterface $logger
    ){
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $start = microtime(true);

        $response = $handler->handle($request);

        $requestMilSec = (microtime(true) - $start) * 1000;

        $labels = [(string)$response->getStatusCode(), $request->getUri()->getPath()];

        try {
            $this
                ->registry
                ->getOrRegisterCounter(
                    '',
                    'http_response_status',
                    '',
                    ['status', 'path']
                )
                ->inc($labels);

            $this
                ->registry
                ->getOrRegisterHistogram(
                    '',
                    'http_response_time',
                    '',
                    ['status', 'path']
                )
                ->observe($requestMilSec, $labels);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $response;
    }
}