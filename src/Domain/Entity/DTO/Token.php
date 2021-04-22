<?php


namespace App\Domain\Entity\DTO;

/**
 * @OA\Schema()
 */
class Token implements \JsonSerializable
{
    /**
     * @OA\Property(format="string")
     */
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return ['token' => $this->token];
    }
}