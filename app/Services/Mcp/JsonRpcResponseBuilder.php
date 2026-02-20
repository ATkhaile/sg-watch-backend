<?php

namespace App\Services\Mcp;

/**
 * JSON-RPC 2.0 Response Builder
 * Builds compliant success and error responses
 */
class JsonRpcResponseBuilder
{
    /**
     * Build success response
     * 
     * @param mixed $result The result data
     * @param mixed $id Request ID
     * @return array JSON-RPC 2.0 success response
     */
    public function success(mixed $result, mixed $id): array
    {
        return [
            'jsonrpc' => '2.0',
            'result' => $result,
            'id' => $id,
        ];
    }
    
    /**
     * Build error response
     * 
     * @param int $code Error code (JSON-RPC standard codes)
     * @param string $message Error message
     * @param mixed $id Request ID (null for parse errors)
     * @param mixed $data Optional additional error data
     * @return array JSON-RPC 2.0 error response
     */
    public function error(int $code, string $message, mixed $id, mixed $data = null): array
    {
        $error = [
            'jsonrpc' => '2.0',
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'id' => $id,
        ];
        
        if ($data !== null) {
            $error['error']['data'] = $data;
        }
        
        return $error;
    }
}
