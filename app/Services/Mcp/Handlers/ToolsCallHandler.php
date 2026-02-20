<?php

namespace App\Services\Mcp\Handlers;

use App\Domain\Mcp\Entity\ToolCallEntity;
use App\Domain\Mcp\UseCase\CallToolMcpUseCase;
use App\Services\Mcp\JsonRpcException;
use App\Services\Mcp\JsonRpcRequest;
use App\Services\Mcp\JsonRpcResponseBuilder;

/**
 * Handler for JSON-RPC tools/call method
 * Executes MCP tools and returns results
 */
class ToolsCallHandler
{
    public function __construct(
        private CallToolMcpUseCase $useCase,
        private JsonRpcResponseBuilder $responseBuilder
    ) {}
    
    /**
     * Handle tools/call request
     * 
     * @param JsonRpcRequest $request JSON-RPC request
     * @return array JSON-RPC response
     */
    public function handle(JsonRpcRequest $request): array
    {
        try {
            // Validate params
            if ($request->params === null || !is_array($request->params)) {
                throw new JsonRpcException(
                    'Params required for tools/call',
                    JsonRpcException::INVALID_PARAMS
                );
            }
            
            $params = $request->params;
            
            // Validate required fields
            if (!isset($params['name'])) {
                throw new JsonRpcException(
                    'Tool name is required in params',
                    JsonRpcException::INVALID_PARAMS
                );
            }
            
            // Create entity for tool execution
            $entity = new ToolCallEntity(
                name: $params['name'],
                arguments: $params['arguments'] ?? []
            );
            
            // Execute tool via use case
            $result = $this->useCase->__invoke($entity);
            
            // Check if execution was successful
            if ($result->getStatusCode() === 200) {
                // Success - format according to MCP protocol
                $textContent = $result->getMessage() ?? 'Tool executed successfully';
                
                // Include result data if available
                if ($result->getResult() !== null) {
                    $resultData = is_array($result->getResult()) 
                        ? json_encode($result->getResult(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                        : (string) $result->getResult();
                    
                    $textContent .= "\n\nResult:\n" . $resultData;
                }
                
                $content = [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $textContent,
                        ]
                    ],
                ];
                
                return $this->responseBuilder->success($content, $request->id);
            }
            
            // Tool execution failed - return as JSON-RPC error
            return $this->responseBuilder->error(
                JsonRpcException::INTERNAL_ERROR,
                $result->getMessage() ?? 'Tool execution failed',
                $request->id,
                ['status_code' => $result->getStatusCode()]
            );
        } catch (JsonRpcException $e) {
            // Re-throw JSON-RPC exceptions
            throw $e;
        } catch (\Exception $e) {
            return $this->responseBuilder->error(
                JsonRpcException::INTERNAL_ERROR,
                'Tool execution error: ' . $e->getMessage(),
                $request->id
            );
        }
    }
}
