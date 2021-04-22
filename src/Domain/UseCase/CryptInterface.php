<?php declare(strict_types=1);


namespace App\Domain\UseCase;


use App\Domain\Exception\PasswordHashException;

interface CryptInterface
{
    /**
     * @throws PasswordHashException
     */
    public function passwordHash(string $password): string;

    public function comparePassword(string $password, string $hash): bool;
}