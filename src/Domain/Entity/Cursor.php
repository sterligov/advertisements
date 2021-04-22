<?php declare(strict_types=1);


namespace App\Domain\Entity;


use Symfony\Component\Validator\Constraints as Assert;

class Cursor
{
    const NEXT_CURSOR = 'next';

    const PREV_CURSOR = 'prev';

    const SORT_ASC = 'asc';

    const SORT_DESC = 'desc';

    private mixed $value = null;

    /**
     * @Assert\AtLeastOneOf({
     *     @Assert\EqualTo("next"),
     *     @Assert\EqualTo("prev")
     * })
     */
    private string $type = self::SORT_ASC;

    /**
     * @Assert\Range(min=1, max=100)
     */
    private int $limit = 10;

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSort(): string
    {
        return $this->type === self::NEXT_CURSOR ? self::SORT_ASC : self::SORT_DESC;
    }

    public function setValue(int|string|null $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}