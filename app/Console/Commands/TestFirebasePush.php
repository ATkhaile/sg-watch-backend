<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class TestFirebasePush extends Command
{
    protected $signature = 'firebase:test-push {token? : FCM device token} {--topic=test : Send to a topic instead} {--validate : Only validate the token without sending}';
    protected $description = 'Send a test push notification via Firebase Cloud Messaging';

    public function handle(): int
    {
        $credentialsPath = base_path(config('services.firebase.credentials_path'));

        if (!file_exists($credentialsPath)) {
            $this->error("Firebase credentials file not found at: {$credentialsPath}");
            return self::FAILURE;
        }

        try {
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $messaging = $factory->createMessaging();

            $deviceToken = $this->argument('token');

            // Validate token only
            if ($deviceToken && $this->option('validate')) {
                $result = $messaging->validateRegistrationTokens([$deviceToken]);
                if (!empty($result['valid'])) {
                    $this->info('Token is valid.');
                } else {
                    $reason = !empty($result['invalid']) ? 'invalid' : 'unknown';
                    $this->error("Token is {$reason}.");
                }
                return self::SUCCESS;
            }

            $notification = Notification::create(
                'Test Notification',
                'Firebase push notification is working!'
            );

            $data = [
                'type' => 'test',
                'timestamp' => now()->toIso8601String(),
            ];

            if ($deviceToken) {
                $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification($notification)
                    ->withData($data);
            } else {
                $topic = $this->option('topic');
                $message = CloudMessage::withTarget('topic', $topic)
                    ->withNotification($notification)
                    ->withData($data);
                $this->info("Sending to topic: {$topic}");
            }

            $messaging->send($message);

            $this->info('Push notification sent successfully!');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
