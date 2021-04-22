<?php


namespace App\Domain\Entity;


interface UniqueEntityInterface extends \JsonSerializable
{
    public function getId(): int|string|null;
}