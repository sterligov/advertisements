<?php declare(strict_types=1);


namespace App\Domain\UseCase;


use App\Domain\Entity\User;
use App\Domain\Exception\InvalidCredentialsException;

interface AuthInterface
{
    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $email, string $pass): string;
}