<?php declare(strict_types=1);


namespace App\Domain\Gateway;


use App\Domain\Entity\Advertising;
use App\Domain\Exception\GatewayException;

interface AdvertisingGatewayInterface extends CursorGatewayInterface
{
    /**
     * @throws GatewayException
     */
    public function create(Advertising $ad): void;

    /**
     * @throws GatewayException
     */
    public function update(Advertising $ad): void;

    /**
     * @throws GatewayException
     */
    public function delete(Advertising $ad): void;

    /**
     * @return Advertising|null
     */
    public function findById(int $id): ?object;

    /**
     * @return Advertising[]
     */
    public function findAllByUserId(int $userId): array;
}