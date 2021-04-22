<?php

include __DIR__ . '/../vendor/autoload.php';

use Middlewares\Utils\Dispatcher;
use Laminas\Diactoros\ServerRequestFactory;

/** @var Psr\Container\ContainerInterface $container */
$container = include __DIR__ . '/../dependencies/dependencies.php';

Dispatcher::run($container->get('middleware.stack'), ServerRequestFactory::fromGlobals());
