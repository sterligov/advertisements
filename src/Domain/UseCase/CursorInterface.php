<?php declare(strict_types=1);


namespace App\Domain\UseCase;


use App\Domain\Entity\Cursor as CursorEntity;
use App\Domain\Entity\DTO\CursorData;
use App\Domain\Exception\CursorException;
use App\Domain\Gateway\CursorGatewayInterface;

interface CursorInterface
{
    /**
     * @throws CursorException
     */
    public function cursor(CursorEntity $cursor): CursorData;

    public function setCursorGateway(CursorGatewayInterface $cursorGateway): void;
}