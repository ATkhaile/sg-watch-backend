<?php

namespace App\Exceptions\Domain;

use App\Enums\StatusCode;
use App\Exceptions\Http\HttpException;
use Exception;

class UnauthorizedException extends HttpException
{
    /**
     * @var string $message 例外メッセージ
     */
    private const MESSAGE = '権限がありません。';

    /**
     * @var int $statusCode HTTPステータスコード
     */
    private const STATUS_CODE = StatusCode::FORBIDDEN;

    /**
     * constructor.
     * @param string $message 例外メッセージ
     * @param int $statusCode HTTPステータスコード
     * @param int $code 例外コード
     * @param Exception|null $previous 例外
     */
    public function __construct(
        string $message = self::MESSAGE,
        int $statusCode = self::STATUS_CODE,
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($statusCode, $message, $code, $previous);
    }
}
