<?php


namespace App\Endpoint\Http\Handler;


use Laminas\Diactoros\Response\TextResponse;
use Prometheus\RegistryInterface;
use Prometheus\RendererInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Endpoint\Http\ResponseStatus;

class MetricsHandler
{
    public function __construct(
        private RegistryInterface $registry,
        private RendererInterface $renderer,
    ){
    }

    /**
     * @OA\Get(
     *   path="/metrics",
     *   summary="Service metrics",
     *   @OA\Response(
     *     response=200,
     *     description="Metrics"
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="Unexpected error",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *   )
     * )
     */
    public function metrics(RequestInterface $request): ResponseInterface
    {
        $metrics = $this->renderer->render($this->registry->getMetricFamilySamples());

        return new TextResponse($metrics, ResponseStatus::HTTP_OK);
    }
}