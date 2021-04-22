<?php declare(strict_types=1);


namespace App\Gateway\Doctrine;


use App\Domain\Entity\Advertising;
use App\Domain\Entity\Cursor;
use App\Domain\Entity\UniqueEntityInterface;
use App\Domain\Exception\CursorException;
use App\Domain\Gateway\AdvertisingGatewayInterface;
use App\Domain\Gateway\CursorEntityManagerInterface;
use Doctrine\ORM\EntityManager;


/**
 * @template T
 * @extends AbstractDoctrineGateway<T>
 */
class AdvertisingGateway extends AbstractDoctrineGateway implements AdvertisingGatewayInterface
{
    public function __construct(
        private CursorEntityManagerInterface $cursorGateway,
        EntityManager $entityManager,
    ) {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Advertising::class));

        $this->cursorGateway->setEntityRepository($this);
    }

    /**
     * @return UniqueEntityInterface[]
     * @throws CursorException
     */
    public function cursor(Cursor $cursor): array
    {
        return $this->cursorGateway->cursor($cursor);
    }

    /**
     * @return Advertising[]
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->findBy(['user_id' => $userId]);
    }
}