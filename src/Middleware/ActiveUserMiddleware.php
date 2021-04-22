<?php declare(strict_types=1);


namespace App\Middleware;


use App\Domain\UseCase\ActiveUserInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ActiveUserMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ActiveUserInterface $activeUser
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        if (isset($user['email'])) {
            $this->activeUser->setUserByEmail($user['email']);
        }

        return $handler->handle($request);
    }
}