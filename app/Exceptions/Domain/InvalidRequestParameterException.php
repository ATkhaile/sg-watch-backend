<?php

namespace App\Exceptions\Domain;

use App\Enums\StatusCode;
use App\Exceptions\Http\HttpException;
use Exception;

class InvalidRequestParameterException extends HttpException
{
    /**
     * @var string $message 例外メッセージ
     */
    private const MESSAGE = 'Invalid request parameter.';

    /**
     * @var int ステータスコード
     */
    private const STATUS_CODE = StatusCode::BAD_REQUEST;

    /**
     * constructor.
     * @param int $statusCode HTTPステータスコード
     * @param string $message 例外メッセージ
     * @param $code int|null 例外コード
     * @param Exception|null $previous 例外
     */
    public function __construct(
        int $statusCode = self::STATUS_CODE,
        string $message = self::MESSAGE,
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($statusCode, $message, $code, $previous);
    }

    /**
     * @var array $fields リクエストパラメータのフィールド
     */
    protected array $fields = [];

    /**
     * @return array<string, mixed>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return void
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }
}
