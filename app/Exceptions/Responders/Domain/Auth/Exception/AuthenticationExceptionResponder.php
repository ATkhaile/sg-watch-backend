<?php

namespace App\Exceptions\Responders\Domain\Auth\Exception;

use App\Exceptions\Responders\ExceptionResponder;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * 認証エラーの発生した例外の場合のレスポンダー
 */
class AuthenticationExceptionResponder implements ExceptionResponder
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], $exception->getStatusCode()));
    }
}
