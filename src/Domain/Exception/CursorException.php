<?php declare(strict_types=1);


namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;

class CursorException extends HttpException
{
    protected int $statusCode = ResponseStatus::HTTP_BAD_REQUEST;
}