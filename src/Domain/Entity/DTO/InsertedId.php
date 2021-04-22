<?php declare(strict_types=1);


namespace App\Domain\Entity\DTO;


class InsertedId implements \JsonSerializable
{
    public function __construct(
        public int|string $id
    ) {
    }

    /**
     * @phpstan-return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return ['inserted_id' => $this->id];
    }
}