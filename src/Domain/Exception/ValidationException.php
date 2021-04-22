<?php declare(strict_types=1);


namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends HttpException
{
    /**
     * @var ConstraintViolationListInterface<ConstraintViolationInterface>
     */
    protected ConstraintViolationListInterface $violations;

    /**
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        int $statusCode = ResponseStatus::HTTP_BAD_REQUEST,
        int $code = 0,
        Throwable $previous = null,
    ) {
        parent::__construct('', $statusCode, $code, $previous);

        $this->violations = $violations;
    }

    public function jsonSerialize(): array
    {
        $errors = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return ['errors' => $errors];
    }
}