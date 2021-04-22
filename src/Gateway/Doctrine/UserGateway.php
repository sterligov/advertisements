<?php declare(strict_types=1);


namespace App\Gateway\Doctrine;


use App\Domain\Entity\User;
use App\Domain\Gateway\UserGatewayInterface;
use Doctrine\ORM\EntityManager;

/**
 * @template T
 * @extends AbstractDoctrineGateway<T>
 */
class UserGateway extends AbstractDoctrineGateway implements UserGatewayInterface
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(User::class));
    }

    /**
     * @return User|null
     */
    public function findByEmail(string $email): ?object
    {
        return $this->findOneBy(['email' => $email]);
    }
}
