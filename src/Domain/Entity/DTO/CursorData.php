<?php declare(strict_types=1);


namespace App\Domain\Entity\DTO;

class CursorData implements \JsonSerializable
{
    /**
     * @param \JsonSerializable[] $data
     */
    public function __construct(
        public array $data = [],
        public int $prevCursor = 0,
        public int $nextCursor = 0
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->data,
            'prevCursor' => $this->prevCursor,
            'nextCursor' => $this->nextCursor,
        ];
    }
}