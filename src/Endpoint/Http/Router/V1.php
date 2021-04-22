<?php


namespace App\Endpoint\Http\Router;


use FastRoute\RouteCollector;

class V1
{
    public function __invoke(RouteCollector $r): void
    {
        $r->addGroup('/v1', function (RouteCollector $r) {
            $r->post('/sign-in', 'App\Endpoint\Http\AuthHandler::signIn');
            $r->post('/sign-up', 'App\Endpoint\Http\UserHandler::create');

            $r->get('/users/{id:\d+}', 'App\Endpoint\Http\UserHandler::findById');
            $r->get('/users/{id:\d+}/advertisements', 'App\Endpoint\Http\AdvertisingHandler::findAllByUserId');
            $r->put('/users/{id:\d+}', 'App\Endpoint\Http\UserHandler::update');
            $r->delete('/users/{id:\d+}', 'App\Endpoint\Http\UserHandler::delete');

            $r->get('/advertisements', 'App\Endpoint\Http\AdvertisingHandler::findAllWithCursor');
            $r->get('/advertisements/{id:\d+}', 'App\Endpoint\Http\AdvertisingHandler::findById');
            $r->post('/advertisements', 'App\Endpoint\Http\AdvertisingHandler::create');
            $r->put('/advertisements/{id:\d+}', 'App\Endpoint\Http\AdvertisingHandler::update');
            $r->delete('/advertisements/{id:\d+}', 'App\Endpoint\Http\AdvertisingHandler::delete');

            $r->get('/health', 'App\Endpoint\Http\HealthHandler::check');
            $r->get('/metrics', 'App\Endpoint\Http\MetricsHandler::metrics');
        });
    }
}