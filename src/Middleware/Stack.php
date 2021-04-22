<?php


namespace App\Middleware;


use Middlewares\AccessLog;
use Middlewares\ContentType;
use Middlewares\Cors;
use Middlewares\Emitter;
use Middlewares\FastRoute;
use Middlewares\JsonPayload;
use Middlewares\RequestHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tuupola\Middleware\JwtAuthentication;

class Stack implements StackInterface
{
    /**
     * @var string[]
     */
    private array $stack = [
        Emitter::class,
        MetricsMiddleware::class,
        Cors::class,
        ErrorHandlerMiddleware::class,
        ContentType::class,
        JwtAuthentication::class,
        FastRoute::class,
        FieldsFilterMiddleware::class,
        JsonPayload::class,
        AccessLog::class,
        ActiveUserMiddleware::class,
        RequestHandler::class,
    ];

    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * @return MiddlewareInterface[]
     */
    public function getStack(): array
    {
        if ($this->container->get('app.mode') === 'dev') {
            $pos = array_search(ErrorHandlerMiddleware::class, $this->stack);
            if ($pos !== false) {
                unset($this->stack[$pos]);
            }
        }

        $stack = [];
        foreach ($this->stack as $id) {
            $stack[] = $this->container->get($id);
        }

        return $stack;
    }
}