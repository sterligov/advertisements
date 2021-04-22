<?php


namespace App\Middleware\Cors;


use Neomerx\Cors\Contracts\AnalysisStrategyInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

class Settings implements AnalysisStrategyInterface
{
    public function __construct(
        private AnalysisStrategyInterface $strategy
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getServerOriginScheme(): string
    {
        return $this->strategy->getServerOriginScheme();
    }

    /**
     * @inheritdoc
     */
    public function getServerOriginHost(): string
    {
        return $this->strategy->getServerOriginHost();
    }

    /**
     * @inheritdoc
     */
    public function getServerOriginPort(): ?int
    {
        return $this->strategy->getServerOriginPort();
    }

    /**
     * @inheritdoc
     */
    public function isPreFlightCanBeCached(RequestInterface $request): bool
    {
        return $this->strategy->isPreFlightCanBeCached($request);
    }

    /**
     * @inheritdoc
     */
    public function getPreFlightCacheMaxAge(RequestInterface $request): int
    {
        return $this->strategy->getPreFlightCacheMaxAge($request);
    }

    /** @inheritdoc */
    public function isForceAddAllowedMethodsToPreFlightResponse(): bool
    {
        return $this->strategy->isForceAddAllowedMethodsToPreFlightResponse();
    }

    /**
     * @inheritdoc
     */
    public function isForceAddAllowedHeadersToPreFlightResponse(): bool
    {
        return $this->strategy->isForceAddAllowedHeadersToPreFlightResponse();
    }

    /**
     * @inheritdoc
     */
    public function isRequestCredentialsSupported(RequestInterface $request): bool
    {
        return $this->strategy->isRequestCredentialsSupported($request);
    }

    /**
     * @inheritdoc
     */
    public function isRequestOriginAllowed(string $requestOrigin): bool
    {
        return $this->strategy->isRequestOriginAllowed($requestOrigin);
    }

    /**
     * @inheritdoc
     */
    public function isRequestMethodSupported(string $method): bool
    {
        return $this->strategy->isRequestMethodSupported($method);
    }

    /**
     * @inheritdoc
     */
    public function isRequestAllHeadersSupported(array $lcHeaders): bool
    {
        return $this->strategy->isRequestAllHeadersSupported($lcHeaders);
    }

    /**
     * @inheritdoc
     */
    public function getRequestAllowedMethods(RequestInterface $request): string
    {
        return $this->strategy->getRequestAllowedMethods($request);
    }

    /**
     * @inheritdoc
     */
    public function getRequestAllowedHeaders(RequestInterface $request): string
    {
        $headers = $this->strategy->getRequestAllowedHeaders($request);

        if ($headers === '' && $this->strategy->isRequestAllHeadersSupported([])) {
            return '*';
        }

        return $headers;
    }

    /**
     * @inheritdoc
     */
    public function getResponseExposedHeaders(RequestInterface $request): string
    {
        return $this->strategy->getResponseExposedHeaders($request);
    }

    /**
     * @inheritdoc
     */
    public function isCheckHost(): bool
    {
        return $this->strategy->isCheckHost();
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->strategy->setLogger($logger);
    }
}