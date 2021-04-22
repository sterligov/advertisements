<?php declare(strict_types=1);


namespace App\Domain\Entity;

use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema(required={"email", "firstName", "lastName", "password"})
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass=\App\Gateway\Doctrine\UserGateway::class)
 * @ORM\EntityListeners({"App\Listener\UserListener"})
*/
class User extends DoctrineEntity implements UniqueEntityInterface
{
    protected array $allowedFieldsToMassWrite = ['id', 'firstName', 'lastName', 'email', 'password'];

    protected array $allowedFieldsToMassRead = ['id', 'firstName', 'lastName', 'email'];

    /**
     * @OA\Property(format="int64")
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected int $id;

    /**
     * @OA\Property()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="first_name")
     */
    protected string $firstName;

    /**
     * @OA\Property()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="last_name")
     */
    protected string $lastName;

    /**
     * @OA\Property(format="email")
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     */
    protected string $email;

    /**
     * @OA\Property(format="string", writeOnly=true)
     * @Assert\Length(min=6)
     * @ORM\Column(type="string")
     */
    protected string $password;

    public function getId(): int|string|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
