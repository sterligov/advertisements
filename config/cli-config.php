<?php

include __DIR__ . '/../vendor/autoload.php';

/** @var Psr\Container\ContainerInterface $container */
$container = include __DIR__ . '/../dependencies/dependencies.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;

$config = new ConfigurationArray($container->get('doctrine.migrations'));
$entityManager = $container->get(Doctrine\ORM\EntityManager::class);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
