<?php declare(strict_types=1);


namespace App\UseCase;


use App\Domain\Exception\PasswordHashException;
use App\Domain\UseCase\CryptInterface;

class Bcrypt implements CryptInterface
{
    /**
     * @throws PasswordHashException
     */
    public function passwordHash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        if (!$hash) {
            throw new PasswordHashException('Invalid password string');
        }

        return $hash;
    }

    public function comparePassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
