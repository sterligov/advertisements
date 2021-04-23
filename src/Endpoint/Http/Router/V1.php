<?php


namespace App\Endpoint\Http\Router;


use FastRoute\RouteCollector;

class V1
{
    public function __invoke(RouteCollector $r): void
    {
        $r->addGroup('/v1', function (RouteCollector $r) {
            $r->post('/sign-in', 'App\Endpoint\Http\Handler\AuthHandler::signIn');
            $r->post('/sign-up', 'App\Endpoint\Http\Handler\UserHandler::create');

            $r->get('/users/{id:\d+}', 'App\Endpoint\Http\Handler\UserHandler::findById');
            $r->get('/users/{id:\d+}/advertisements', 'App\Endpoint\Http\Handler\AdvertisingHandler::findAllByUserId');
            $r->put('/users/{id:\d+}', 'App\Endpoint\Http\Handler\UserHandler::update');
            $r->delete('/users/{id:\d+}', 'App\Endpoint\Http\Handler\UserHandler::delete');

            $r->get('/advertisements', 'App\Endpoint\Http\Handler\AdvertisingHandler::findAllWithCursor');
            $r->get('/advertisements/{id:\d+}', 'App\Endpoint\Http\Handler\AdvertisingHandler::findById');
            $r->post('/advertisements', 'App\Endpoint\Http\Handler\AdvertisingHandler::create');
            $r->put('/advertisements/{id:\d+}', 'App\Endpoint\Http\Handler\AdvertisingHandler::update');
            $r->delete('/advertisements/{id:\d+}', 'App\Endpoint\Http\Handler\AdvertisingHandler::delete');

            $r->get('/health', 'App\Endpoint\Http\Handler\HealthHandler::check');
            $r->get('/metrics', 'App\Endpoint\Http\Handler\MetricsHandler::metrics');
        });
    }
}