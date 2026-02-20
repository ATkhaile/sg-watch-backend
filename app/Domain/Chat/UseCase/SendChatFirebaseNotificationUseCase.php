<?php

namespace App\Domain\Chat\UseCase;

use App\Models\FcmToken;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\PusherInfo;
use App\Enums\PushType;

class SendChatFirebaseNotificationUseCase
{
    public function execute(array $messageData, string $chatType = 'direct', array $recipientUserIds = []): void
    {
        try {
            if (!empty($recipientUserIds)) {
                $fcmTokens = FcmToken::whereIn('user_id', $recipientUserIds)->get();
            } else {
                $fcmTokens = FcmToken::all();
            }

            if ($fcmTokens->isEmpty()) {
                Log::info('No FCM tokens found for chat notification', [
                    'recipient_user_ids' => $recipientUserIds
                ]);
                return;
            }

            $accessToken = $this->getFirebaseAccessToken();
            if (!$accessToken) {
                Log::error('Failed to get Firebase access token');
                return;
            }
            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $projectId = $setting ? $setting->firebase_project_id : config('services.firebase.project_id');
            if (!$projectId) {
                Log::error('Firebase project ID not configured');
                return;
            }

            $senderName = $this->getSenderName($messageData);
            $title = $senderName;
            $body = $this->formatMessageBody($messageData);

            foreach ($fcmTokens as $fcmToken) {
                if (isset($messageData['user_id']) && $fcmToken->user_id == $messageData['user_id']) {
                    continue;
                }

                $this->sendToToken($fcmToken->fcm_token, $title, $body, $messageData, $accessToken, $projectId);
            }
        } catch (Exception $e) {
            Log::error('Exception in SendChatFirebaseNotificationUseCase: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    protected function getSenderName(array $messageData): string
    {
        if (isset($messageData['sender_name'])) {
            return $messageData['sender_name'];
        }

        if (isset($messageData['user_name'])) {
            return $messageData['user_name'];
        }

        if (isset($messageData['user_id'])) {
            $user = User::find($messageData['user_id']);
            if ($user) {
                return $user->full_name ?? 'User';
            }
        }

        return 'New Message';
    }

    protected function formatMessageBody(array $messageData): string
    {
        if (isset($messageData['message_type']) && $messageData['message_type'] !== 'text') {
            return match ($messageData['message_type']) {
                'image' => 'Image',
                'file' => ($messageData['file_name'] ?? 'File'),
                'video' => 'Video',
                'audio' => 'Audio',
                default => 'New message'
            };
        }

        return $messageData['message'] ?? 'New message';
    }

    protected function sendToToken(
        string $fcmToken,
        string $title,
        string $body,
        array $data,
        string $accessToken,
        string $projectId
    ): void {
        try {
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $avatarImageUrl = (string)($data['sender_avatar'] ?? $data['user_avatar'] ?? '');

            $contentImageUrl = '';
            $messageType = $data['message_type'] ?? 'text';
            $fileUrl = $data['file_url'] ?? '';
            $fileType = $data['file_type'] ?? '';

            $isImageMessage = $messageType === 'image' ||
                ($messageType === 'file' && !empty($fileType) && str_starts_with($fileType, 'image/')) ||
                (!empty($fileUrl) && preg_match('/\.(jpg|jpeg|png|gif|webp)(\?|$)/i', $fileUrl));

            if ($isImageMessage && !empty($fileUrl)) {
                $contentImageUrl = (string)$fileUrl;
            }

            $notificationImageUrl = !empty($contentImageUrl) ? $contentImageUrl : $avatarImageUrl;
            $conversationId = (string)($data['user_id'] ?? '');

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'redirectType' => 'chat',
                        'chatType' => 'direct',
                        'messageId' => (string)($data['id'] ?? ''),
                        'senderId' => (string)($data['user_id'] ?? ''),
                        'senderName' => (string)($data['sender_name'] ?? $data['user_name'] ?? ''),
                        'senderAvatar' => (string)($data['sender_avatar'] ?? $data['user_avatar'] ?? ''),
                        'receiverId' => (string)($data['receiver_id'] ?? ''),
                        'contentType' => (string)($data['message_type'] ?? 'text'),
                        'fileUrl' => (string)($data['file_url'] ?? ''),
                        'fileName' => (string)($data['file_name'] ?? ''),
                        'fileType' => (string)($data['file_type'] ?? ''),
                        'timestamp' => (string)($data['created_at'] ?? ''),
                        'imageUrl' => $notificationImageUrl,
                        'avatarUrl' => $avatarImageUrl,
                        'contentImageUrl' => $contentImageUrl,
                        'conversationId' => $conversationId,
                    ],
                    'android' => [
                        'priority' => 'HIGH',
                        'notification' => array_filter([
                            'sound' => 'default',
                            'channel_id' => 'chat_messages',
                            'image' => $notificationImageUrl ?: null,
                        ], fn($v) => $v !== null),
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1,
                                'alert' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'mutable-content' => 1,
                                'thread-id' => $conversationId,
                                'category' => 'CHAT_MESSAGE',
                            ],
                        ],
                        'fcm_options' => $notificationImageUrl ? [
                            'image' => $notificationImageUrl,
                        ] : null,
                    ],
                ],
            ];

            if ($payload['message']['apns']['fcm_options'] === null) {
                unset($payload['message']['apns']['fcm_options']);
            }

            $httpClient = new Client();
            $response = $httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() === 200) {
                Log::info('Chat Firebase notification sent successfully', [
                    'fcm_token' => substr($fcmToken, 0, 20) . '...',
                    'title' => $title,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to send chat Firebase notification: ' . $e->getMessage(), [
                'fcm_token' => substr($fcmToken, 0, 20) . '...',
            ]);
        }
    }

    protected function getFirebaseAccessToken(): ?string
    {
        try {
            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $credentialsPath = $setting ? $setting->firebase_credentials_path : config('services.firebase.credentials_path');
            if (!$credentialsPath) {
                Log::error('Firebase credentials path not configured');
                return null;
            }

            $fullPath = base_path($credentialsPath);
            if (!file_exists($fullPath)) {
                Log::error('Firebase credentials file not found', ['path' => $credentialsPath]);
                return null;
            }

            $json = json_decode(file_get_contents($fullPath), true);

            if (!$json || !isset($json['client_email']) || !isset($json['private_key'])) {
                Log::error('Invalid Firebase credentials format');
                return null;
            }

            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT'
            ];

            $now = time();
            $payload = [
                'iss' => $json['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ];

            $jwt = $this->generateJwt($header, $payload, $json['private_key']);

            $httpClient = new Client();
            $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['access_token'] ?? null;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Exception while getting Firebase access token: ' . $e->getMessage());
            return null;
        }
    }

    protected function generateJwt(array $header, array $payload, string $privateKey): string
    {
        $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $data = $base64UrlHeader . '.' . $base64UrlPayload;

        if (!openssl_sign($data, $signature, $privateKey, 'sha256WithRSAEncryption')) {
            throw new Exception('Failed to sign JWT with private key');
        }

        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $data . '.' . $base64UrlSignature;
    }
}
