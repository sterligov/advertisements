<?php declare(strict_types=1);


namespace App\Domain\Entity;


use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @OA\Schema()
 * @ORM\Entity
 * @ORM\Table(name="advertising")
 * @ORM\Entity(repositoryClass=\App\Gateway\Doctrine\AdvertisingGateway::class)
 */
class Advertising extends DoctrineEntity implements UniqueEntityInterface
{
    /**
     *
     * @var string[]
     */
    protected array $allowedFieldsToMassWrite = ['name', 'description', 'price'];

    /**
     * @var string[]
     */
    protected array $allowedFieldsToMassRead = ['id', 'name', 'description', 'price'];

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
     * @ORM\Column(type="string")
     */
    protected string $name;

    /**
     * @OA\Property()
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    protected string $description;

    /**
     * @OA\Property()
     * @Assert\PositiveOrZero()
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    protected float $price;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected User $user;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): int|string|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
