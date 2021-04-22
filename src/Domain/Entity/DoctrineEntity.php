<?php declare(strict_types=1);


namespace App\Domain\Entity;


use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\MappedSuperclass
 */
abstract class DoctrineEntity
{
    /**
     * @var string[]
     */
    protected array $allowedFieldsToMassWrite = [];

    /**
     * @var string[]
     */
    protected array $allowedFieldsToMassRead = [];

    /**
     * @param array<string, mixed> $data
     */
    public function setAttributes(array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->allowedFieldsToMassWrite)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        $data = [];
        foreach ($this->allowedFieldsToMassRead as $field) {
            $data[$field] = $this->$field;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->getAttributes();
    }
}