<?php

namespace App\Jobs;

use App\Enums\SenderType;
use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class PushEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $notificationId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $notification = Notification::find($this->notificationId);
            if (!$notification) {
                Log::warning('Notification not found', ['notification_id' => $this->notificationId]);
                return;
            }

            $notificationData = [
                'title' => $this->replaceVariables($notification->title),
                'content' => $this->replaceVariables($notification->content),
                'mail_from' => SenderType::getMailFrom($notification->sender_type),
            ];

            // Get all users for email notification
            $receivers = User::all();

            $userNotifications = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($receivers as $receiver) {
                $pushSuccess = true;

                $userNoti = new UserNotification;
                $userNoti->notification_id = $notification->id;
                $userNoti->push_type = $notification->push_type;
                $userNoti->user_id = $receiver->id;

                try {
                    $mail = $receiver->email;
                    Mail::to($mail)->send(new NotificationMail($notificationData));
                } catch (Exception $e) {
                    $errorCount++;
                    $pushSuccess = false;
                    Log::error('Error sending email in PushEmail job: ' . $e->getMessage(), [
                        'receiver_email' => $mail,
                        'notification_id' => $this->notificationId
                    ]);
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
                    Log::info('Email notification processing completed in PushEmail job', [
                        'notification_id' => $this->notificationId,
                        'success_count' => $successCount,
                        'error_count' => $errorCount,
                    ]);
                } catch (Exception $e) {
                    Log::error('Failed to save user notifications in PushEmail job: ' . $e->getMessage(), [
                        'notification_id' => $this->notificationId
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Exception occurred in PushEmail job: ' . $e->getMessage(), [
                'notification_id' => $this->notificationId
            ]);
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
}
