<?php

namespace App\Exceptions\Responders\Exceptions\Domain;

use App\Enums\StatusCode;
use App\Exceptions\Domain\InvalidRequestParameterException;
use App\Exceptions\Responders\ExceptionResponder;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * InvalidRequestParameterExceptionに対するレスポンダー
 */
class InvalidRequestParameterExceptionResponder implements ExceptionResponder
{
    /**
     * 例外レスポンダー
     * @param InvalidRequestParameterException $exception
     * @return HttpResponseException
     */
    public function __invoke($exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], StatusCode::BAD_REQUEST));
    }
}
