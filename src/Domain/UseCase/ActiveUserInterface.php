<?php


namespace App\Domain\UseCase;


use App\Domain\Entity\User;
use App\Domain\Exception\UnexpectedActiveUserException;

interface ActiveUserInterface
{
    public function setUserByEmail(string $email): void;

    /**
     * @throws UnexpectedActiveUserException
     */
    public function getUser(): User;

    /**
     * @throws UnexpectedActiveUserException
     */
    public function getUserId(): int;
}