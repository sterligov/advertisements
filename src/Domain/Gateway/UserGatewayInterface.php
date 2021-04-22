<?php declare(strict_types=1);


namespace App\Domain\Gateway;


use App\Domain\Entity\User;
use App\Domain\Exception\GatewayException;

interface UserGatewayInterface
{
    /**
     * @return User|null
     */
    public function findByEmail(string $email): ?object;

    /**
     * @return User|null
     */
    public function findById(int $id): ?object;

    /**
     * @throws GatewayException
     */
    public function create(User $user): void;

    /**
     * @throws GatewayException
     */
    public function update(User $user): void;

    /**
     * @throws GatewayException
     */
    public function delete(User $user): void;
}