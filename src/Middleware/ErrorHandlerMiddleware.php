<?php declare(strict_types=1);


namespace App\Middleware;


use App\Domain\Exception\BaseException;
use App\Domain\Exception\HttpException;
use App\Endpoint\Http\ResponseStatus;
use Doctrine\ORM\EntityNotFoundException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    const EXCEPTION_STATUS = [
        \JsonException::class => ResponseStatus::HTTP_BAD_REQUEST,
        EntityNotFoundException::class => ResponseStatus::HTTP_NOT_FOUND,
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $error) {
            $statusCode = ResponseStatus::HTTP_INTERNAL_SERVER_ERROR;

            switch (true) {
                case isset(self::EXCEPTION_STATUS[$error::class]):
                    $statusCode = self::EXCEPTION_STATUS[$error::class];
                    $error = ['error' => $error->getMessage()];
                    break;
                case $error instanceof HttpException:
                    $statusCode = $error->getStatusCode();
                    $error = $error->jsonSerialize();
                    break;
                case $error instanceof BaseException:
                    $error = $error->jsonSerialize();
                    break;
                default:
                    $error = ['error' => 'Internal server error'];
            }

            return new JsonResponse($error, $statusCode);
        }
    }
}