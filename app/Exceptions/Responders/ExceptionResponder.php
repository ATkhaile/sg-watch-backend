<?php

namespace App\Exceptions\Responders;

use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * 例外レスポンダーインターフェース
 */
interface ExceptionResponder
{
    /**
     * 例外レスポンダー
     * @param mixed $exception
     * @return mixed
     */
    public function __invoke(mixed $exception): HttpResponseException;
}
