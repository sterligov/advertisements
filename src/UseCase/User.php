<?php declare(strict_types=1);


namespace App\UseCase;


use App\Domain\Exception\ValidationException;
use App\Domain\Gateway\UserGatewayInterface;
use App\Domain\UseCase\UserInterface;
use App\Domain\Entity\User as UserEntity;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class User implements UserInterface
{
    public function __construct(
        private UserGatewayInterface $userGateway,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @return \App\Domain\Entity\User|null
     */
    public function findByEmail(string $email): ?object
    {
        return $this->userGateway->findByEmail($email);
    }

    /**
     * @return \App\Domain\Entity\User|null
     */
    public function findById(int $id): ?object
    {
        return $this->userGateway->findById($id);
    }

    /**
     * @throws EntityNotFoundException
     * @throws \App\Domain\Exception\GatewayException
     */
    public function delete(UserEntity $user): void
    {
        $this->userGateway->delete($user);
    }

    /**
     * @throws ValidationException
     * @throws \App\Domain\Exception\GatewayException
     * @throws \App\Domain\Exception\PasswordHashException
     */
    public function create(UserEntity $user): void
    {
        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        $this->userGateway->create($user);
    }

    /**
     * @throws ValidationException
     * @throws \App\Domain\Exception\GatewayException
     * @throws \App\Domain\Exception\PasswordHashException
     */
    public function update(UserEntity $user): void
    {
        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        $this->userGateway->update($user);
    }
}