<?php declare(strict_types=1);



namespace App\Domain\UseCase;


use App\Domain\Entity\Advertising;
use App\Domain\Entity\Cursor;
use App\Domain\Entity\DTO\CursorData;

interface AdvertisingInterface
{
    public function findAllWithCursor(Cursor $cursor): CursorData;

    public function create(Advertising $ad): void;

    public function update(Advertising $ad): void;

    public function delete(int $id, int $userID): void;

    /**
     * @return Advertising|null
     */
    public function findById(int $id): ?object;

    /**
     * @return Advertising[]
     */
    public function findAllByUserId(int $userId): array;
}