<?php

namespace App\Http\Actions\Mcp;

use App\Domain\Mcp\Entity\ToolCallEntity;
use App\Enums\StatusCode;

class McpToolExecutor
{
    private array $handlers = [];

    public function __construct()
    {
        $this->registerHandlers();
    }

    private function registerHandlers(): void
    {
        // Register handlers for each domain
        $this->handlers['news'] = new ToolHandlers\NewsToolHandler();
        $this->handlers['category'] = new ToolHandlers\CategoryToolHandler();
        // Add more handlers as needed
        // $this->handlers['tag'] = new ToolHandlers\TagToolHandler();
    }

    public function execute(ToolCallEntity $entity): ToolCallEntity
    {
        $toolName = $entity->getName();
        
        // Parse tool name (format: "domain_action", e.g., "news_create")
        $parts = explode('_', $toolName);
        
        if (count($parts) < 2) {
            $entity->setStatusCode(StatusCode::BAD_REQUEST);
            $entity->setMessage("Invalid tool name format. Expected 'domain_action'");
            return $entity;
        }
        $domain = $parts[0];
        $action = $parts[1];
        if (!isset($this->handlers[$domain])) {
            $entity->setStatusCode(StatusCode::NOT_FOUND);
            $entity->setMessage("No handler found for domain: {$domain}");
            return $entity;
        }
        $handler = $this->handlers[$domain];
        try {
            $result = $handler->handle($action, $entity->getArguments());
            $entity->setResult($result);
            $entity->setStatusCode(StatusCode::OK);
            $entity->setMessage('Tool executed successfully');
        } catch (\Exception $e) {
            $entity->setStatusCode(StatusCode::INTERNAL_ERR);
            $entity->setMessage($e->getMessage());
            $entity->setResult(null);
        }

        return $entity;
    }
}
