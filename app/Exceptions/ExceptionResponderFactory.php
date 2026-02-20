<?php

namespace App\Exceptions;

use App\Events\Error\ServerErrorEvent;
use App\Exceptions\Responders\ExceptionResponder;
use App\Exceptions\Responders\GeneralExceptionResponder;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * 例外処理のレスポンダー生成ファクトリー
 */
class ExceptionResponderFactory
{
    /**
     * 例外のためのレスポンダーの決定
     * @param Exception|Throwable $exception
     * @return ExceptionResponder
     */
    public static function make(Exception|Throwable $exception): ExceptionResponder
    {
        $responderName = self::getResponderName($exception);
        if (!class_exists($responderName)) {
            Log::error($exception->getMessage());
            Log::error($exception->getPrevious()?->getTraceAsString());
            Log::error($exception->getTraceAsString());

            Log::debug(
                '例外のためのレスポンダーが見つからないため、汎用レスポンダーを返却します。name=' . $responderName
            );

            event(new ServerErrorEvent($exception));
            return new GeneralExceptionResponder;
        }

        Log::debug($exception->getMessage());
        Log::debug($exception->getPrevious()?->getTraceAsString());
        Log::debug($exception->getTraceAsString());

        return new $responderName;
    }

    /**
     * 例外のためのレスポンダー名を取得する
     * @param Exception|Throwable $exception
     * @return string
     */
    private static function getResponderName(Exception|Throwable $exception): string
    {
        // クラス名を取得する
        $exceptionName = get_class($exception);
        $exceptionName = str_replace('App\\', '', $exceptionName); // App\が重複するため削除する

        return 'App\Exceptions\Responders\\' . $exceptionName . 'Responder';
    }
}
