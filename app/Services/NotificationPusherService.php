<?php

namespace App\Services;

use App\Enums\ActiveStatus;
use App\Enums\PushType;
use App\Enums\SenderType;
use App\Jobs\PushEmail;
use App\Mail\NotificationMail;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\NotificationPush;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserNotificationHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use GuzzleHttp\Client;
use Pusher\Pusher;
use App\Models\PusherInfo;

class NotificationPusherService
{
    public function pushNotification(int $notificationId): void
    {
        try {
            $notification = Notification::find($notificationId);
            if (!$notification) {
                Log::warning('Notification not found', ['notification_id' => $notificationId]);
                return;
            }

            if ($notification->push_type === PushType::EMAIL) {
                PushEmail::dispatch($notificationId);
                return;
            }

            $notificationData = [
                'title' => $this->replaceVariables($notification->title),
                'content' => $this->replaceVariables($notification->content),
                'image' => $notification->image_url,
                'mail_from' => SenderType::getMailFrom($notification->sender_type),
            ];

            $receivers = collect();
            switch ($notification->push_type) {
                case PushType::PUSHER:
                    $receivers = User::all();
                    break;
                case PushType::FIREBASE:
                    $receivers = FcmToken::all();
                    break;
                case PushType::LINE:
                    $receivers = User::whereNotNull('users.id')
                        ->join('my_sns', 'users.id', '=', 'my_sns.user_id')
                        ->join('my_sns_bot', 'my_sns.id', '=', 'my_sns_bot.my_sns_id')
                        ->join('line_users', 'my_sns_bot.channel_id', '=', 'line_users.channel_id')
                        ->whereNull('my_sns.deleted_at')
                        ->whereNull('my_sns_bot.deleted_at')
                        ->whereNull('line_users.deleted_at')
                        ->select([
                            'users.id as id',
                            'line_users.line_user_id as line_user_id',
                            'my_sns_bot.channel_access_token as channel_access_token'
                        ])
                        ->get();
                    break;
                default:
                    break;
            }

            $userNotifications = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($receivers as $receiver) {
                $pushSuccess = true;

                $userNoti = new UserNotification;
                $userNoti->notification_id = $notification->id;
                $userNoti->push_type = $notification->push_type;
                try {
                    switch ($notification->push_type) {
                        case PushType::EMAIL:
                            $userNoti->user_id = $receiver->id;
                            $mail = $receiver->email;
                            Mail::to($mail)->send(new NotificationMail($notificationData));
                            break;

                        case PushType::PUSHER:
                            $userNoti->user_id = $receiver->id;
                            $this->pushPusher([
                                'id' => $notification->id,
                                'message' => $notificationData['content'],
                                'user_id' => $receiver->id,
                            ]);
                            break;

                        case PushType::FIREBASE:
                            $userNoti->fcm_token_id = $receiver->id;
                            $this->pushFirebase($receiver, $notificationData);
                            break;

                        case PushType::LINE:
                            $userNoti->user_id = $receiver->id;
                            $userNoti->line_user_id = $receiver->line_user_id;
                            $this->pushLine($receiver->channel_access_token, $receiver->line_user_id, $notificationData);
                            break;

                        default:
                            break;
                    }
                } catch (Exception $e) {
                    $errorCount++;
                    $pushSuccess = false;
                    Log::error('Error processing receiver: ' . $e->getMessage());
                }

                if ($pushSuccess) {
                    $successCount++;
                    $userNotifications[] = $userNoti;
                }
            }

            // Save user notifications
            if (!empty($userNotifications)) {
                try {
                    $notification->user_notifications()->saveMany($userNotifications);
                    Log::info('Notification processing completed', [
                        'success_count' => $successCount,
                        'error_count' => $errorCount,
                    ]);
                } catch (Exception $e) {
                    Log::error('Failed to save user notifications: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            Log::error('Exception occurred in pushNotification: ' . $e->getMessage());
        }
    }

    public function pushMessage(int $notificationPushId): void
    {
        $logger = Log::channel('log_notification_push');
        try {
            $notificationPush = NotificationPush::leftJoin('app_pages', 'app_pages.id', '=', 'notification_pushs.app_page_id')
                ->where('notification_pushs.id', $notificationPushId)
                ->select(
                    'notification_pushs.*',
                    'app_pages.index as app_page_index'
                )
                ->first();
            if (!$notificationPush) {
                $logger->warning('NotificationPush not found', ['notification_push_id' => $notificationPushId]);
                return;
            }

            $notificationData = [
                'title' => $notificationPush->title,
                'content' => $notificationPush->message,
                'image' => $notificationPush->img_url,
                'sound' => $notificationPush->sound,
                'redirect_type' => $notificationPush->redirect_type,
                'app_page_index' => $notificationPush->app_page_index,
                'attach_file' => $notificationPush->attach_file_url,
                'attach_link' => $notificationPush->attach_link,
            ];

            $receivers = collect();
            if ($notificationPush->all_user_flag) {
                $receivers = FcmToken::where('active_status', ActiveStatus::ACTIVE)->whereNotNull('user_id')->get();
            } else {
                $receivers = FcmToken::query()
                    ->join('user_notification_pushs', 'fcm_tokens.user_id', '=', 'user_notification_pushs.user_id')
                    ->where('user_notification_pushs.notification_push_id', $notificationPushId)
                    ->where('fcm_tokens.active_status', ActiveStatus::ACTIVE)
                    ->select('fcm_tokens.*')
                    ->get();
            }

            $userNotificationHistories = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($receivers as $receiver) {
                $pushSuccess = true;

                try {
                    $this->pushFirebase($receiver, $notificationData);
                } catch (Exception $e) {
                    $errorCount++;
                    $pushSuccess = false;
                    $logger->error('Error processing receiver: ' . $e->getMessage(), [
                        'fcm_token_id' => $receiver->id,
                    ]);
                }

                if ($pushSuccess) {
                    $successCount++;
                    $userNotificationHistories[] = [
                        'user_id' => $receiver->user_id ?? null,
                        'notification_push_id' => $notificationPush->id,
                        'fcm_token_id' => $receiver->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($userNotificationHistories)) {
                try {
                    UserNotificationHistory::insert($userNotificationHistories);
                    $logger->info('Notification push processing completed', [
                        'notification_push_id' => $notificationPushId,
                        'success_count' => $successCount,
                        'error_count' => $errorCount,
                    ]);
                } catch (Exception $e) {
                    $logger->error('Failed to save user notification histories: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            $logger->error('Exception occurred in pushMessage: ' . $e->getMessage());
        }
    }


    protected function pushFirebase(FcmToken $receiver, array $notificationData): void
    {
        try {
            $accessToken = $this->getFirebaseAccessToken();
            if (!$accessToken) {
                Log::channel('log_notification_push')->error('Failed to get Firebase access token');
                return;
            }

            $setting = PusherInfo::where('push_type', PushType::FIREBASE)->first();
            $projectId = $setting ? $setting->firebase_project_id : config('services.firebase.project_id');
            if (!$projectId) {
                Log::channel('log_notification_push')->error('Firebase project ID not configured');
                return;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $iosSound = $notificationData['sound'] ?? 'default';


            $payload = [
                'message' => [
                    'token' => $receiver->fcm_token,
                    'notification' => [
                        'title' => $notificationData['title'],
                        'body' => $notificationData['content'],
                        // 'image' => $notificationData['image'],
                    ],
                    'android' => [
                        'notification' => [
                            'sound' => explode('.', $iosSound)[0],
                        ],
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => $iosSound,
                                'badge' => 1,
                            ],
                        ],
                    ],
                    'data' => [
                        'sound' => $iosSound,
                        'redirect_type' => (string)($notificationData['redirect_type'] ?? ''),
                        'app_page_index'   => $notificationData['app_page_index'] !== null
                            ? (string)$notificationData['app_page_index']
                            : '',
                        'attach_file'   => (string)($notificationData['attach_file'] ?? ''),
                        'attach_link'   => (string)($notificationData['attach_link'] ?? ''),
                    ],
                ],
            ];
            if (isset($notificationData['image']) && $notificationData['image']) {
                $payload['message']['notification']['image'] = $notificationData['image'];
                $payload['message']['apns']['payload']['aps']['mutable-content'] = 1;
                $payload['message']['apns']['fcm_options']['image'] = $notificationData['image'];
                $payload['message']['data']['rightImageUrl'] = (string)$notificationData['image'];
            }

            $httpClient = new Client;
            $response = $httpClient->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);

            Log::channel('log_notification_push')->info('Firebase push notification response', [
                'fcm_token' => $receiver->fcm_token,
                'response_body' => $response->getBody()->getContents(),
                'payload' => $payload
            ]);
        } catch (Exception $e) {
            Log::channel('log_notification_push')->error('Exception occurred while sending Firebase notification: ' . $e->getMessage());
        }
    }

    protected function pushLine(string $accessToken, string $userId, array $notificationData): void
    {
        try {
            $httpClient = new Client;
            $response = $httpClient->post('https://api.line.me/v2/bot/message/push', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $userId,
                    'messages' => [
                        [
                            'type' => 'text',
                            'text' => $notificationData['content'],
                        ]
                    ]
                ]
            ]);

            Log::info('LINE push notification response', [
                'line_user_id' => $userId,
                'response_body' => $response->getBody()->getContents(),
            ]);
        } catch (\Exception $e) {
            Log::error('Exception occurred while sending LINE notification: ' . $e->getMessage());
        }
    }

    protected function pushPusher(array $data): void
    {
        try {
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => false
            ];
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
            $pusher->trigger('chat-channel', 'chat-event', $data);
            Log::info('Pusher notification sent successfully', [
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Exception occurred while sending Pusher notification: ' . $e->getMessage());
        }
    }

    protected function replaceVariables(string $content, array $variables = []): string
    {
        $replacements = [];
        foreach ($variables as $key => $value) {
            $replacements['{' . $key . '}'] = $value ?? '';
        }

        return strtr($content, $replacements);
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
            if (!file_exists(base_path($credentialsPath))) {
                Log::error('Firebase credentials file not found', [
                    'path' => $credentialsPath,
                ]);
                return null;
            }

            $json = json_decode(file_get_contents(base_path($credentialsPath)), true);

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
                'exp' => $now + 3600, // 1 hour expiration
            ];

            $jwt = $this->generateJwt($header, $payload, $json['private_key']);

            $httpClient = new Client;
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
            Log::error('Exception occurred while getting Firebase access token: ' . $e->getMessage());
            return null;
        }
    }

    protected function generateJwt($header, $payload, $privateKey): string
    {
        try {
            $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
            $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

            $data = $base64UrlHeader . '.' . $base64UrlPayload;

            if (!openssl_sign($data, $signature, $privateKey, 'sha256WithRSAEncryption')) {
                throw new Exception('Failed to sign JWT with private key');
            }

            $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

            return $data . '.' . $base64UrlSignature;
        } catch (Exception $e) {
            Log::error('Exception occurred while generating JWT: ' . $e->getMessage());
            throw $e;
        }
    }
}
