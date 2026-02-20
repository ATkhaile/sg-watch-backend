<?php

namespace App\Domain\Google\Components;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleComponent
{
    public static function getAccessToken(string $code, string $redirectUrl = null, $ssoProvider = null): ?array
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'client_id' => $ssoProvider ? $ssoProvider->client_key : config('services.google.client_id'),
                    'client_secret' => $ssoProvider ? $ssoProvider->client_secret : config('services.google.client_secret'),
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUrl ? $redirectUrl : config('services.google.redirect_url'),
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData;
            }

            $responseData = json_decode($response->getBody()->getContents(), true);

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getUserInfo(string $accessToken): ?array
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData;
            }

            $responseData = json_decode($response->getBody()->getContents(), true);
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function verifyAppToken(string $token): ?array
    {
        try {
            $payload = self::decodeJwtPayload($token);

            if ($payload && isset($payload['email'])) {
                $userInfo = [
                    'email' => $payload['email'],
                    'name' => $payload['name'] ?? $payload['email'],
                    'picture' => $payload['picture'] ?? null,
                    'email_verified' => $payload['email_verified'] ?? false,
                ];

                return $userInfo;
            }

            $httpClient = new Client();
            $response = $httpClient->get('https://www.googleapis.com/oauth2/v1/tokeninfo', [
                'query' => [
                    'id_token' => $token,
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData;
            }

            $responseData = json_decode($response->getBody()->getContents(), true);

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function decodeJwtPayload(string $token): ?array
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return null;
            }

            $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
            return json_decode($payload, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
