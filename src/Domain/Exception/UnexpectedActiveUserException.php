<?php


namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;

class UnexpectedActiveUserException extends HttpException
{
    protected int $statusCode = ResponseStatus::HTTP_UNAUTHORIZED;
}