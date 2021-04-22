<?php


namespace App\Tests\Unit\UseCase;


use App\Domain\Entity\Cursor;
use App\Domain\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CursorTest extends TestCase
{
    public function testCursor_invalidValidation()
    {
        $cursorEntity = new Cursor();

        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $violationList->method('count')
            ->willReturn(1);

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->method('validate')
            ->with($cursorEntity)
            ->willReturn($violationList);

        $this->expectException(ValidationException::class);

        $cursorUseCase = new \App\UseCase\Cursor($validatorMock);
        $cursorUseCase->cursor($cursorEntity);
    }
}