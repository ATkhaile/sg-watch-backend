<?php

namespace App\Exceptions\Responders\Illuminate\Validation;

use App\Enums\StatusCode;
use App\Exceptions\Responders\ExceptionResponder;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * バリデーション例外レスポンダー
 */
class ValidationExceptionResponder implements ExceptionResponder
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], StatusCode::BAD_REQUEST));
    }
}
