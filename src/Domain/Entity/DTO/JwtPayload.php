<?php declare(strict_types=1);


namespace App\Domain\Entity\DTO;

class JwtPayload implements \JsonSerializable
{
    public function __construct(
        public int $iat,
        public int $exp,
        public int $id,
        public string $email,
    ) {
    }

    /**
     * @phpstan-return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'iat' => $this->iat,
            'exp' => $this->exp,
            'email' => $this->email,
            'id' => $this->id,
        ];
    }
}