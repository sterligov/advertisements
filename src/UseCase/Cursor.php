<?php declare(strict_types=1);


namespace App\UseCase;


use App\Domain\Entity\DTO\CursorData;
use App\Domain\Exception\CursorException;
use App\Domain\Exception\ValidationException;
use App\Domain\Gateway\CursorGatewayInterface;
use App\Domain\Entity\Cursor as CursorEntity;
use App\Domain\UseCase\CursorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Cursor implements CursorInterface
{
    private CursorGatewayInterface $cursorGateway;

    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function setCursorGateway(CursorGatewayInterface $cursorGateway): void
    {
        $this->cursorGateway = $cursorGateway;
    }

    /**
     * @throws ValidationException
     * @throws CursorException
     */
    public function cursor(CursorEntity $cursor): CursorData
    {
        $errors = $this->validator->validate($cursor);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $cursor = clone($cursor);
        $cursor->setLimit($cursor->getLimit() + 1);

        $objects = $this->cursorGateway->cursor($cursor);

        $nObjects = count($objects);
        if ($nObjects === 0) {
            return new CursorData();
        }

        $prevCursor = -1;
        $nextCursor = -1;

        if ($cursor->getValue() === null) {
            $prevCursor = 0;
        }

        if ($nObjects < $cursor->getLimit()) {
            if ($cursor->getType() === CursorEntity::PREV_CURSOR) {
                $prevCursor = 0;
            } else {
                $nextCursor = 0;
            }
        } else {
            $nObjects--;
            $objects = array_slice($objects, 0, $nObjects);
        }

        if ($cursor->getType() === CursorEntity::SORT_DESC) {
            $objects = array_reverse($objects);
        }

        if ($prevCursor === -1) {
            $prevCursor = $objects[0]->getId();
        }

        if ($nextCursor === -1) {
            $nextCursor = $objects[$nObjects - 1]->getId();
        }

        return new CursorData($objects, $prevCursor, $nextCursor);
    }
}