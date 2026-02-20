<?php

namespace App\Exceptions\Infrastructure\SendGrid;

use App\Infrastructure\SendGrid\SendMailEntity;
use Exception;

/**
 * SendGridでのメール送信時にエラーが発生した場合の例外
 */
class InvalidRequestException extends \Exception
{
    private SendMailEntity $entity;

    public function __construct(
        string $errorMessage,
        SendMailEntity $entity,
        Exception|null $previous = null
    ) {
        parent::__construct($errorMessage, 0, $previous);

        $this->entity = $entity;
    }

    public function getEntity(): SendMailEntity
    {
        return $this->entity;
    }
}
