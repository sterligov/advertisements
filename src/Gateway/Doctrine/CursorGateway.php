<?php declare(strict_types=1);


namespace App\Gateway\Doctrine;


use App\Domain\Entity\Cursor;
use App\Domain\Gateway\CursorEntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @template T
 */
class CursorGateway implements CursorEntityManagerInterface
{
    /**
     * @phpstan-var EntityRepository<T> $repository
     */
    private ?EntityRepository $repository;

    /**
     * @phpstan-param EntityRepository<T> $repository
     */
    public function setEntityRepository(EntityRepository $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @throws \RuntimeException
     */
    public function cursor(Cursor $cursor): array
    {
        if ($this->repository === null) {
            throw new \RuntimeException('Null repository');
        }

        $compareOp = $cursor->getType() === Cursor::NEXT_CURSOR ? '>' : '<';

        $query = $this->repository->createQueryBuilder('t');

        if ($cursor->getValue() !== null) {
            $query
                ->where("t.id $compareOp :value")
                ->setParameter('value', $cursor->getValue());
        }

        return $query
            ->setMaxResults($cursor->getLimit())
            ->orderBy('t.id', $cursor->getSort())
            ->getQuery()
            ->execute();
    }
}