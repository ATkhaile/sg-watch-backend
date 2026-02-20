<?php

namespace App\Exceptions\Responders;

use App\Exceptions\Http\HttpException;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * HttpExceptionの例外レスポンダー
 */
class HttpExceptionResponder implements ExceptionResponder
{
    /**
     * 例外レスポンダー
     * @param HttpException $exception
     * @return HttpResponseException
     */
    public function __invoke($exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], $exception->getStatusCode()));
    }
}
