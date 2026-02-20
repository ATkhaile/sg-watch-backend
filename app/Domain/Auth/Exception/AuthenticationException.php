<?php

namespace App\Domain\Auth\Exception;

use App\Enums\StatusCode;
use App\Exceptions\Http\HttpException;
use Exception;

class AuthenticationException extends HttpException
{
    private const MESSAGE = 'Unauthorized.';

    private const STATUS_CODE = StatusCode::UNAUTHORIZED;

    public function __construct(
        int $statusCode = self::STATUS_CODE,
        string $message = self::MESSAGE,
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct(
            statusCode: $statusCode,
            message: $message,
            code: $code,
            previous: $previous
        );
    }
}
