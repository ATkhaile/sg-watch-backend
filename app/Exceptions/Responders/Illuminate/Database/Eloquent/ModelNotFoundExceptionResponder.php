<?php

namespace App\Exceptions\Responders\Illuminate\Database\Eloquent;

use App\Enums\StatusCode;
use App\Exceptions\Responders\ExceptionResponder;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Eloquentモデル未検出例外レスポンダー
 */
class ModelNotFoundExceptionResponder implements ExceptionResponder
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], StatusCode::NOT_FOUND));
    }
}
