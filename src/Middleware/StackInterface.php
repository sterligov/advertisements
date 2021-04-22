<?php


namespace App\Middleware;


use Psr\Http\Server\MiddlewareInterface;

interface StackInterface
{
    /**
     * @return MiddlewareInterface[]
     */
    public function getStack(): array;
}