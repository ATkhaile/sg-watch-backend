<?php

namespace App\Exceptions\Http;

use Exception;

/**
 * HTTP通信における例外クラス
 */
class HttpException extends Exception
{
    /**
     * @var int $statusCode HTTPステータスコード
     */
    protected int $statusCode;

    /**
     * constructor.
     * @param int $statusCode HTTPステータスコード
     * @param string|null $message 例外メッセージ
     * @param $code int|null 例外コード
     * @param Exception|null $previous
     */
    public function __construct(int $statusCode, string $message = null, $code = 0, Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct(message: $message, code: $statusCode, previous: $previous);
    }

    /**
     * @return int HTTPステータスコード
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
