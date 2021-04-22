<?php declare(strict_types=1);


namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;
use Throwable;

class HttpException extends BaseException
{
    protected int $statusCode = 0;

    public function __construct(string $message = '', int $statusCode = 0, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if ($statusCode !== 0) {
            $this->statusCode = $statusCode;
        }
    }

    public function getStatusCode(): int
    {
        if (empty($this->statusCode)) {
            return ResponseStatus::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $this->statusCode;
    }
}