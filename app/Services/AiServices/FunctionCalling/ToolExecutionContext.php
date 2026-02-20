<?php

namespace App\Services\AiServices\FunctionCalling;

use App\Models\AiApplication;
use App\Models\AiProvider;
use App\Models\User;

class ToolExecutionContext
{
    public function __construct(
        public readonly int $conversationId,
        public readonly int $appId,
        public readonly AiApplication $application,
        public readonly AiProvider $provider,
        public readonly string $fromSource,
        public readonly ?int $userId = null,
        public readonly ?int $messageId = null,
        public readonly ?array $uploadedFiles = null,
        public readonly ?string $originalMessage = null,
        public readonly ?User $user = null, // Current authenticated user with full information
    ) {}
}
