<?php

$reader = include __DIR__ . '/../config/doctrine-annotations.php';

$dependencies = [
    // Middlewares

    'router.v1' => DI\autowire(App\Endpoint\Http\Router\V1::class),

    App\Middleware\Jwt\Error::class => DI\autowire(App\Middleware\Jwt\Error::class),

    Tuupola\Middleware\JwtAuthentication::class => function (Psr\Container\ContainerInterface $c) {
        return new Tuupola\Middleware\JwtAuthentication([
            'ignore' => $c->get('jwt.ignore'),
            'secret' => $c->get('jwt.secret'),
            'algorithm' => [$c->get('jwt.alg')],
            'attribute' => $c->get('jwt.attribute'),
            'error' => $c->get(App\Middleware\Jwt\Error::class),
        ]);
    },

    FastRoute\Dispatcher::class => function (Psr\Container\ContainerInterface $c) {
        return FastRoute\cachedDispatcher($c->get('router.v1'), [
            'cacheFile' => $c->get('router.cache'),
            'cacheDisabled' => $c->get('app.mode') !== 'prod'
        ]);
    },

    Middlewares\FastRoute::class => DI\autowire(Middlewares\FastRoute::class),

    Middlewares\AccessLog::class => DI\autowire(Middlewares\AccessLog::class),

    Middlewares\ContentType::class => DI\autowire(Middlewares\ContentType::class)
        ->constructor(['json']),

    App\Middleware\ErrorHandlerMiddleware::class => DI\autowire(App\Middleware\ErrorHandlerMiddleware::class),

    App\Middleware\FieldsFilterMiddleware::class => DI\autowire(App\Middleware\FieldsFilterMiddleware::class),

    App\Middleware\ActiveUserMiddleware::class => DI\autowire(App\Middleware\ActiveUserMiddleware::class),

    Middlewares\JsonPayload::class => function (Psr\Container\ContainerInterface $c) {
        return (new Middlewares\JsonPayload())
            ->associative($c->get('json.associative'))
            ->depth($c->get('json.depth'));
    },

    Middlewares\RequestHandler::class => function (Psr\Container\ContainerInterface $c) {
        return new Middlewares\RequestHandler($c);
    },

    Neomerx\Cors\Contracts\AnalyzerInterface::class => function (Psr\Container\ContainerInterface $c) {
        $settings = new Neomerx\Cors\Strategies\Settings();
        $settings
            ->init($c->get('cors.scheme'), $c->get('cors.host'), $c->get('cors.port'))
            ->setAllowedMethods($c->get('cors.methods'))
            ->enableAllHeadersAllowed()
            ->enableAddAllowedMethodsToPreFlightResponse()
            ->enableAddAllowedMethodsToPreFlightResponse()
            ->enableAllOriginsAllowed()
            ->setCredentialsSupported();

        $settings = new App\Middleware\Cors\Settings($settings);

        return Neomerx\Cors\Analyzer::instance($settings);
    },

    'middleware.stack' => function (Psr\Container\ContainerInterface $c) {
        return (new App\Middleware\Stack($c))->getStack();
    },

    // Gateway

    App\Domain\Gateway\UserGatewayInterface::class => DI\autowire(App\Gateway\Doctrine\UserGateway::class),

    App\Domain\Gateway\AdvertisingGatewayInterface::class => DI\autowire(App\Gateway\Doctrine\AdvertisingGateway::class),

    App\Domain\Gateway\CursorEntityManagerInterface::class => DI\autowire(App\Gateway\Doctrine\CursorGateway::class),

    // UseCase

    App\Domain\UseCase\CursorInterface::class => DI\autowire(App\UseCase\Cursor::class),

    App\Domain\UseCase\UserInterface::class => DI\autowire(App\UseCase\User::class),

    App\Domain\UseCase\ActiveUserInterface::class => DI\autowire(App\UseCase\ActiveUser::class),

    App\Domain\UseCase\CryptInterface::class => DI\autowire(App\UseCase\Bcrypt::class),

    App\Domain\UseCase\AdvertisingInterface::class => DI\autowire(App\UseCase\Advertising::class),

    App\Domain\UseCase\AuthInterface::class => DI\autowire(App\UseCase\JwtAuth::class)
        ->constructor(DI\get('jwt.secret'), DI\get('jwt.ttl')),

    // Other

    Prometheus\RendererInterface::class => DI\autowire(Prometheus\RenderTextFormat::class),

    Prometheus\Storage\Adapter::class => DI\autowire(Prometheus\Storage\Redis::class)
        ->constructor([
            'host' => DI\get('redis.host'),
            'port' => DI\get('redis.port'),
        ]),

    Prometheus\RegistryInterface::class => DI\autowire(Prometheus\CollectorRegistry::class),

    Monolog\Handler\HandlerInterface::class => DI\autowire(Monolog\Handler\StreamHandler::class)
        ->constructor(DI\get('log.path')),

    Psr\Log\LoggerInterface::class => DI\autowire(Monolog\Logger::class)
        ->constructor(DI\get('log.name'), [DI\get(Monolog\Handler\HandlerInterface::class)]),

    Doctrine\ORM\EntityManager::class => DI\factory([Doctrine\ORM\EntityManager::class, 'create'])
        ->parameter('connection', DI\get('doctrine.dbparams'))
        ->parameter('config', DI\get('doctrine.config')),

    Symfony\Component\Validator\Validator\ValidatorInterface::class => function (Psr\Container\ContainerInterface $c) {
        return Symfony\Component\Validator\Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    },

    'doctrine.config' => function (Psr\Container\ContainerInterface $c) use ($reader) {
        $driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, $c->get('doctrine.entityPath'));
        $config = Doctrine\ORM\Tools\Setup::createConfiguration($c->get('doctrine.isDevMode'));
        $config->setMetadataDriverImpl($driver);

        $config->setEntityListenerResolver(new App\Listener\ListenerResolver($c));

        return $config;
    },

    App\Listener\UserListener::class => DI\autowire(),

    // Handlers

    'App\Endpoint\Http\HealthHandler::check' => [
        DI\autowire(App\Endpoint\Http\HealthHandler::class),
        'check'
    ],

    'App\Endpoint\Http\MetricsHandler::metrics' => [
        DI\autowire(App\Endpoint\Http\MetricsHandler::class),
        'metrics'
    ],

    'App\Endpoint\Http\AuthHandler::signIn' => [
        DI\autowire(App\Endpoint\Http\AuthHandler::class),
        'signIn'
    ],

    'App\Endpoint\Http\UserHandler::create' => [
        DI\autowire(App\Endpoint\Http\UserHandler::class),
        'create'
    ],

    'App\Endpoint\Http\UserHandler::update' => [
        DI\autowire(App\Endpoint\Http\UserHandler::class),
        'update'
    ],

    'App\Endpoint\Http\UserHandler::delete' => [
        DI\autowire(App\Endpoint\Http\UserHandler::class),
        'delete'
    ],

    'App\Endpoint\Http\UserHandler::findByEmail' => [
        DI\autowire(App\Endpoint\Http\UserHandler::class),
        'findByEmail'
    ],

    'App\Endpoint\Http\AdvertisingHandler::findAllWithCursor' => [
        DI\autowire(App\Endpoint\Http\AdvertisingHandler::class),
        'findAllWithCursor'
    ],

    'App\Endpoint\Http\AdvertisingHandler::create' => [
        DI\autowire(App\Endpoint\Http\AdvertisingHandler::class),
        'create'
    ],

    'App\Endpoint\Http\AdvertisingHandler::update' => [
        DI\autowire(App\Endpoint\Http\AdvertisingHandler::class),
        'update'
    ],

    'App\Endpoint\Http\AdvertisingHandler::delete' => [
        DI\autowire(App\Endpoint\Http\AdvertisingHandler::class),
        'delete'
    ],

    'App\Endpoint\Http\AdvertisingHandler::findById' => [
        DI\autowire(App\Endpoint\Http\AdvertisingHandler::class),
        'findById'
    ],

    // Commands

    App\Endpoint\Cli\GenerateUserCommand::class => DI\autowire(),
];

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(
    __DIR__ . '/../config/config.php',
    $dependencies,
);

return $containerBuilder->build();
