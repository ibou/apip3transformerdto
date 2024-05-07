<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    public function __construct(string $errorStatus = 'Invalid request.', int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($statusCode, $errorStatus);
    }
}
