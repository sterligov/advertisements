<?php declare(strict_types=1);


namespace App\Domain\Gateway;


use App\Domain\Entity\Cursor;
use App\Domain\Entity\UniqueEntityInterface;
use App\Domain\Exception\CursorException;

interface CursorGatewayInterface
{
    /**
     * @return UniqueEntityInterface[]
     * @throws  CursorException
     */
    public function cursor(Cursor $cursor): array;
}