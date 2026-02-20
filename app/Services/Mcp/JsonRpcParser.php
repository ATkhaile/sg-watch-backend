<?php

namespace App\Services\Mcp;

/**
 * JSON-RPC 2.0 Parser
 * Parses and validates incoming JSON-RPC requests
 */
class JsonRpcParser
{
    /**
     * Parse JSON-RPC 2.0 request
     * 
     * @param array $data Raw request data
     * @return JsonRpcRequest Parsed request
     * @throws JsonRpcException If request is invalid
     */
    public function parse(array $data): JsonRpcRequest
    {
        $this->validate($data);
        
        return new JsonRpcRequest(
            jsonrpc: $data['jsonrpc'],
            method: $data['method'],
            params: $data['params'] ?? null,
            id: $data['id'] ?? null
        );
    }
    
    /**
     * Validate JSON-RPC 2.0 format
     * 
     * @param array $data Request data
     * @throws JsonRpcException If invalid
     */
    private function validate(array $data): void
    {
        // Check jsonrpc version
        if (!isset($data['jsonrpc']) || $data['jsonrpc'] !== '2.0') {
            throw new JsonRpcException(
                'Invalid JSON-RPC version. Expected "2.0"',
                JsonRpcException::INVALID_REQUEST
            );
        }
        
        // Check method exists
        if (!isset($data['method']) || !is_string($data['method'])) {
            throw new JsonRpcException(
                'Method field is required and must be a string',
                JsonRpcException::INVALID_REQUEST
            );
        }
        
        // Validate params if present (should be array or object)
        if (isset($data['params']) && !is_array($data['params']) && !is_object($data['params'])) {
            throw new JsonRpcException(
                'Params must be an array or object',
                JsonRpcException::INVALID_PARAMS
            );
        }
    }
}
