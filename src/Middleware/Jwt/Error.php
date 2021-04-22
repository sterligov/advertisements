<?php


namespace App\Middleware\Jwt;


use Psr\Http\Message\ResponseInterface;

class Error
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __invoke(ResponseInterface $response, array $arguments): int
    {
        return $response->getBody()->write(json_encode(
            ['error' => $arguments['message']]
        ));
    }
}