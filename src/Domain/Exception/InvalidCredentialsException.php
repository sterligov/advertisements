<?php declare(strict_types=1);

namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;

class InvalidCredentialsException extends HttpException
{
    protected int $statusCode = ResponseStatus::HTTP_UNAUTHORIZED;
}