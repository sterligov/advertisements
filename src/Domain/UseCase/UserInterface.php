<?php declare(strict_types=1);


namespace App\Domain\UseCase;


use App\Domain\Entity\User;

interface UserInterface
{
    /**
     * @return User|null
     */
    public function findByEmail(string $email): ?object;

    /**
     * @return User|null
     */
    public function findById(int $id): ?object;

    public function delete(User $user): void;

    public function create(User $user): void;

    public function update(User $user): void;
}