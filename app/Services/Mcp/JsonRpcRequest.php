<?php

namespace App\Services\Mcp;

/**
 * JSON-RPC 2.0 Request DTO
 */
class JsonRpcRequest
{
    public function __construct(
        public readonly string $jsonrpc,
        public readonly string $method,
        public readonly mixed $params = null,
        public readonly mixed $id = null
    ) {}
}
