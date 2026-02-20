<?php

namespace App\Services\Mcp\Handlers;

use App\Domain\Mcp\Entity\McpToolEntity;
use App\Domain\Mcp\UseCase\GetAllToolsForMcpUseCase;
use App\Services\Mcp\JsonRpcException;
use App\Services\Mcp\JsonRpcRequest;
use App\Services\Mcp\JsonRpcResponseBuilder;

/**
 * Handler for JSON-RPC tools/list method
 * Returns list of available MCP tools
 */
class ToolsListHandler
{
    public function __construct(
        private GetAllToolsForMcpUseCase $useCase,
        private JsonRpcResponseBuilder $responseBuilder
    ) {}

    /**
     * Handle tools/list request
     * 
     * @param JsonRpcRequest $request JSON-RPC request
     * @return array JSON-RPC response
     */
    public function handle(JsonRpcRequest $request): array
    {
        try {
            $entity = $this->useCase->__invoke(new McpToolEntity());
            $tools = $entity->getMcpTools() ?? [];
            $result = [
                'tools' => collect($tools)->map(function ($tool) {
                    return [
                        'name' => $tool['name'],
                        'description' => $tool['description'] ?? '',
                        'inputSchema' => $tool['input_schema'] ?? (object)[
                            'type' => 'object',
                            'properties' => new \stdClass(),
                        ],
                    ];
                })->values()->toArray(),
            ];

            $response = $this->responseBuilder->success($result, $request->id);

            return $response;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('🔧 TOOLS/LIST ERROR', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->responseBuilder->error(
                JsonRpcException::INTERNAL_ERROR,
                'Failed to retrieve tools: ' . $e->getMessage(),
                $request->id
            );
        }
    }
}
