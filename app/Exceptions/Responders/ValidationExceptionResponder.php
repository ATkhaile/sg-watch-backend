<?php

namespace App\Exceptions\Responders;

use App\Enums\StatusCode;
use App\Exceptions\Http\HttpException;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * ValidationExceptionの例外レスポンダー
 */
class ValidationExceptionResponder implements ExceptionResponder
{
    /**
     * 例外レスポンダー
     * @param HttpException $exception
     * @return HttpResponseException
     */
    public function __invoke($exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], StatusCode::BAD_REQUEST));
    }
}
