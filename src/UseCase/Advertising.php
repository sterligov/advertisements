<?php declare(strict_types=1);


namespace App\UseCase;


use App\Domain\Entity\DTO\CursorData;
use App\Domain\Exception\PermissionException;
use App\Domain\Exception\ValidationException;
use App\Domain\Gateway\AdvertisingGatewayInterface;
use App\Domain\UseCase\AdvertisingInterface;
use App\Domain\Entity\Cursor as CursorEntity;
use App\Domain\Entity\Advertising as AdEntity;
use App\Domain\UseCase\CursorInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Advertising implements AdvertisingInterface
{
    public function __construct(
        private AdvertisingGatewayInterface $advertisingGateway,
        private CursorInterface $cursor,
        private ValidatorInterface $validator,
    ) {
        $this->cursor->setCursorGateway($this->advertisingGateway);
    }

    /**
     * @throws \App\Domain\Exception\CursorException
     */
    public function findAllWithCursor(CursorEntity $cursor): CursorData
    {
        return $this->cursor->cursor($cursor);
    }

    /**
     * @throws ValidationException
     * @throws \App\Domain\Exception\GatewayException
     */
    public function create(AdEntity $ad): void
    {
        $errors = $this->validator->validate($ad);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->advertisingGateway->create($ad);
    }

    /**
     * @throws EntityNotFoundException
     * @throws PermissionException
     * @throws ValidationException
     * @throws \App\Domain\Exception\GatewayException
     */
    public function update(AdEntity $ad): void
    {
        $errors = $this->validator->validate($ad);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $storageAd = $this->advertisingGateway->findById($ad->getId());
        if ($storageAd === null) {
            throw new EntityNotFoundException('Advertising not found');
        }

        if ($storageAd->getUser()->getId() !== $ad->getUser()?->getId()) {
            throw new PermissionException('Not enough permissions to update entity');
        }

        $this->advertisingGateway->update($ad);
    }

    /**
     * @throws EntityNotFoundException
     * @throws PermissionException
     * @throws \App\Domain\Exception\GatewayException
     */
    public function delete(int $id, int $userId): void
    {
        $ad = $this->advertisingGateway->findById($id);
        if ($ad === null) {
            throw new EntityNotFoundException('Advertising not found');
        }

        if ($ad->getUser()->getId() !== $userId) {
            throw new PermissionException('Not enough permissions to delete entity');
        }

        $this->advertisingGateway->delete($ad);
    }

    /**
     * @return \App\Domain\Entity\Advertising|null
     */
    public function findById(int $id): ?object
    {
        return $this->advertisingGateway->findById($id);
    }

    /**
     * @return \App\Domain\Entity\Advertising[]
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->advertisingGateway->findAllByUserId($userId);
    }
}