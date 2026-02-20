<?php

namespace App\Exceptions\Responders\Exceptions\Domain;

use App\Exceptions\Responders\ExceptionResponder;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * ドメインで発生した例外の場合のレスポンダー
 */
abstract class DomainExceptionResponder implements ExceptionResponder
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], $exception->getStatusCode()));
    }
}
