<?php


namespace App\Listener;


use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Psr\Container\ContainerInterface;

class ListenerResolver extends DefaultEntityListenerResolver
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function resolve($className)
    {
        return $this->container->get($className);
    }
}
