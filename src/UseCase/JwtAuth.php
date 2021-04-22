<?php declare(strict_types=1);

namespace App\UseCase;


use App\Domain\Entity\DTO\JwtPayload;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\UseCase\AuthInterface;
use App\Domain\UseCase\CryptInterface;
use App\Domain\UseCase\UserInterface;
use Firebase\JWT\JWT;

class JwtAuth implements AuthInterface
{
    public function __construct(
        private string $secretKey,
        private int $ttl,
        private UserInterface $user,
        private CryptInterface $crypt
    ){
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $email, string $password): string
    {
        $user = $this->user->findByEmail($email);
        if ($user === null) {
            throw new InvalidCredentialsException('Invalid username or password');
        }

        if (!$this->crypt->comparePassword($password, $user->getPassword())) {
            throw new InvalidCredentialsException('Invalid username or password');
        }

        return JWT::encode(
            new JwtPayload(
                time(),
                time() + $this->ttl,
                $user->getId(),
                $user->getEmail(),
            ),
            $this->secretKey,
        );
    }
}
