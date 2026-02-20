<?php

namespace App\Services\AiServices\Traits;

trait HttpClientTrait
{
    protected function httpGet(string $url, array $headers = [], int $timeout = 30): string|false
    {
        $headerString = "Content-Type: application/json\r\n";
        foreach ($headers as $key => $value) {
            $headerString .= "{$key}: {$value}\r\n";
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => $headerString,
                'ignore_errors' => true,
                'timeout' => $timeout,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        return file_get_contents($url, false, $context);
    }

    protected function httpPost(string $url, array $data, array $headers = [], int $timeout = 30): string|false
    {
        $headerString = "Content-Type: application/json\r\n";
        foreach ($headers as $key => $value) {
            $headerString .= "{$key}: {$value}\r\n";
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headerString,
                'content' => json_encode($data),
                'ignore_errors' => true,
                'timeout' => $timeout,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        return file_get_contents($url, false, $context);
    }

    /**
     * HTTP POST with status code validation
     * Returns response body on success (2xx), throws exception on error
     */
    protected function httpPostWithValidation(string $url, array $data, array $headers = [], int $timeout = 30): string
    {
        $headerString = "Content-Type: application/json\r\n";
        foreach ($headers as $key => $value) {
            $headerString .= "{$key}: {$value}\r\n";
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headerString,
                'content' => json_encode($data),
                'ignore_errors' => true,
                'timeout' => $timeout,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        // Check HTTP status code from response headers
        $statusCode = $this->getHttpStatusCode($http_response_header ?? []);

        if ($statusCode >= 400) {
            $errorMessage = 'HTTP Error ' . $statusCode;
            if ($response !== false) {
                $decoded = json_decode($response, true);
                $errorMessage = $decoded['message'] ?? $decoded['error'] ?? $response;
            }
            throw new \Exception($errorMessage);
        }

        if ($response === false) {
            throw new \Exception('Failed to connect to server');
        }

        return $response;
    }

    /**
     * Extract HTTP status code from response headers
     */
    protected function getHttpStatusCode(array $headers): int
    {
        if (empty($headers)) {
            return 0;
        }

        // First header contains status line like "HTTP/1.1 200 OK"
        if (preg_match('/HTTP\/\d+\.?\d*\s+(\d{3})/', $headers[0], $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }
}
