<?php

namespace App\Exceptions\Responders;

use App\Enums\StatusCode;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * 共通的な例外レスポンダー
 */
class GeneralExceptionResponder implements ExceptionResponder
{
    /**
     * 例外レスポンダー
     * @param Exception $exception
     * @return HttpResponseException
     */
    public function __invoke($exception): HttpResponseException
    {
        return new HttpResponseException(response()->json([], StatusCode::INTERNAL_ERR));
    }
}
