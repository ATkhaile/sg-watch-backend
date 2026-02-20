<?php

namespace App\Services\Mcp\Handlers;

use App\Services\Mcp\JsonRpcRequest;
use App\Services\Mcp\JsonRpcResponseBuilder;

/**
 * Handler for JSON-RPC initialize method
 * MCP protocol requires initialization handshake before tool usage
 */
class InitializeHandler
{
    public function __construct(
        private JsonRpcResponseBuilder $responseBuilder
    ) {}

    /**
     * Handle initialize request
     * 
     * @param JsonRpcRequest $request JSON-RPC request
     * @return array JSON-RPC response
     */
    public function handle(JsonRpcRequest $request): array
    {
        // Use the client's protocol version for compatibility
        $clientProtocolVersion = $request->params['protocolVersion'] ?? '2024-11-05';
        
        $result = [
            'protocolVersion' => $clientProtocolVersion, 
            'capabilities' => [
                'tools' => [
                    'listChanged' => true,
                ],
            ],
            'serverInfo' => [
                'name' => 'elemental_cloud_mcp',
                'version' => '1.0.0',
            ],
        ];

        return $this->responseBuilder->success($result, $request->id);
    }
}
