<?php


namespace App\Domain\Exception;


abstract class BaseException extends \Exception implements \JsonSerializable
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return ['error' => $this->getMessage()];
    }
}