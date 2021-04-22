<?php

$loader = include __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;


AnnotationRegistry::registerFile(__DIR__ . '/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
AnnotationRegistry::registerAutoloadNamespace('Symfony\Component\Validator\Constraints', __DIR__ . '/../vendor/symfony/validator');
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return new AnnotationReader();
