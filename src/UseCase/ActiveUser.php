<?php


namespace App\UseCase;


use App\Domain\Entity\User;
use App\Domain\Exception\UnexpectedActiveUserException;
use App\Domain\Gateway\UserGatewayInterface;
use App\Domain\UseCase\ActiveUserInterface;

class ActiveUser implements ActiveUserInterface
{
    private ?User $user = null;

    public function __construct(
        private UserGatewayInterface $userGateway
    ) {
    }

    public function setUserByEmail(string $email): void
    {
        $this->user = $this->userGateway->findByEmail($email);
    }

    /**
     * @throws UnexpectedActiveUserException
     */
    public function getUser(): User
    {
        if ($this->user === null) {
            throw new UnexpectedActiveUserException('Unexpected active user');
        }

        return $this->user;
    }

    /**
     * @throws UnexpectedActiveUserException
     */
    public function getUserId(): int
    {
        if ($this->user === null) {
            throw new UnexpectedActiveUserException('Unexpected active user');
        }

        return $this->user->getId();
    }
}