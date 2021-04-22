<?php


namespace App\Domain\Gateway;


use Doctrine\ORM\EntityRepository;


interface CursorEntityManagerInterface extends CursorGatewayInterface
{
    /**
     * @template T
     * @phpstan-param EntityRepository<T> $repository
     */
    public function setEntityRepository(EntityRepository $repository): void;
}