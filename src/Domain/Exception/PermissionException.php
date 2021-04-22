<?php


namespace App\Domain\Exception;


use App\Endpoint\Http\ResponseStatus;

class PermissionException extends HttpException
{
    protected int $statusCode = ResponseStatus::HTTP_BAD_REQUEST;
}