<?php


namespace App\Gateway\Doctrine;


use App\Domain\Entity\Advertising;
use App\Domain\Exception\GatewayException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @template T
 * @extends EntityRepository<T>
 */
abstract class AbstractDoctrineGateway extends EntityRepository
{
    /**
     * @throws GatewayException
     */
    public function create(object $entity): void
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (ORMException|OptimisticLockException $e) {
            throw new GatewayException($e->getMessage());
        }
    }

    /**
     * @throws GatewayException
     */
    public function update(object $entity): void
    {
        try {
            $this->getEntityManager()->merge($entity);
            $this->getEntityManager()->flush();
        } catch (ORMException|OptimisticLockException $e) {
            throw new GatewayException($e->getMessage());
        }
    }

    /**
     * @throws GatewayException
     */
    public function delete(object $entity): void
    {
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (ORMException|OptimisticLockException $e) {
            throw new GatewayException($e->getMessage());
        }
    }

    public function findById(int $id): ?object
    {
        return $this->find($id);
    }
}