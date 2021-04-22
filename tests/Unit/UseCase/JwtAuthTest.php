<?php


namespace App\Tests\Unit\UseCase;


use App\Domain\Entity\User;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\UseCase\CryptInterface;
use App\Domain\UseCase\UserInterface;
use App\UseCase\JwtAuth;
use PHPUnit\Framework\TestCase;

class JwtAuthTest extends TestCase
{
    private static User $user;

    private static string $password = 'pass';

    private static string $email = 'test@test.com';

    public static function setUpBeforeClass(): void
    {
        $user = new User();
        $user->setId(1);
        $user->setEmail(self::$email);
        $user->setPassword('hashed_pass');

        self::$user = $user;
    }

    public function testSignIn_ok()
    {
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('findByEmail')
            ->with(self::$email)
            ->willReturn(self::$user);

        $cryptMock = $this->createMock(CryptInterface::class);
        $cryptMock->method('comparePassword')
            ->with(self::$password, self::$user->getPassword())
            ->willReturn(true);

        $jwt = new JwtAuth('key', 100, $userMock, $cryptMock);
        $this->assertNotEmpty($jwt->signIn(self::$email, self::$password));
    }

    public function testSignIn_userNotFound()
    {
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('findByEmail')
            ->with(self::$email)
            ->willReturn(null);

        $cryptMock = $this->createMock(CryptInterface::class);

        $this->expectException(InvalidCredentialsException::class);

        $jwt = new JwtAuth('key', 100, $userMock, $cryptMock);
        $jwt->signIn(self::$email, self::$password);
    }

    public function testSignIn_invalidPassword()
    {
        $userMock = $this->createMock(UserInterface::class);
        $userMock->method('findByEmail')
            ->with(self::$email)
            ->willReturn(self::$user);

        $cryptMock = $this->createMock(CryptInterface::class);
        $cryptMock->method('comparePassword')
            ->with(self::$password, self::$user->getPassword())
            ->willReturn(false);

        $this->expectException(InvalidCredentialsException::class);

        $jwt = new JwtAuth('key', 100, $userMock, $cryptMock);
        $jwt->signIn(self::$email, self::$password);
    }
}