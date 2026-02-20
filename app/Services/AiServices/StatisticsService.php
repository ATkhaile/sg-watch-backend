<?php

namespace App\Services\AiServices;

use App\Domain\AiAppChat\Entity\GetTokenUsageStatisticsRequestEntity;
use App\Domain\AiAppChat\Entity\GetUserTokenUsageRequestEntity;
use App\Domain\AiAppChat\Entity\GetUserConversationsRequestEntity;
use App\Domain\AiAppChat\Entity\GetAppTokenUsageRequestEntity;
use App\Models\AiAppMessage;
use App\Models\AiApplication;
use App\Models\AiMessageConversation;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class StatisticsService
{
    public function __construct(
        private AiAppMessage $aiAppMessage,
        private AiApplication $aiApplication,
        private AiMessageConversation $aiMessageConversation,
    ) {}

    public function getTokenUsageStatistics(GetTokenUsageStatisticsRequestEntity $entity): array
    {
        $dateFormat = match ($entity->period) {
            'daily' => '%Y-%m-%d',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m-%d',
        };

        $baseQuery = $this->aiAppMessage
            ->where('ai_app_messages.status', 'success');

        if ($entity->app_id !== null) {
            $baseQuery->where('ai_app_messages.app_id', $entity->app_id);
        }

        if ($entity->start_date) {
            $baseQuery->whereDate('ai_app_messages.created_at', '>=', $entity->start_date);
        }
        if ($entity->end_date) {
            $baseQuery->whereDate('ai_app_messages.created_at', '<=', $entity->end_date);
        }

        if ($entity->user_id !== null) {
            $baseQuery->where('ai_app_messages.from_account_id', $entity->user_id);
        }

        if ($entity->group_by_app) {
            return $this->getStatisticsGroupedByApp($baseQuery, $dateFormat, $entity->period);
        }

        return $this->getStatisticsGroupedByUser($baseQuery, $dateFormat, $entity->period);
    }

    private function getStatisticsGroupedByUser($baseQuery, string $dateFormat, string $period): array
    {
        $results = (clone $baseQuery)
            ->join('users', 'ai_app_messages.from_account_id', '=', 'users.id')
            ->selectRaw('ai_app_messages.from_account_id as user_id')
            ->selectRaw("CONCAT(users.first_name, ' ', users.last_name) as user_name")
            ->selectRaw('users.email as user_email')
            ->selectRaw("DATE_FORMAT(ai_app_messages.created_at, '{$dateFormat}') as period")
            ->selectRaw('SUM(ai_app_messages.message_tokens) as total_input_tokens')
            ->selectRaw('SUM(ai_app_messages.answer_tokens) as total_output_tokens')
            ->selectRaw('SUM(ai_app_messages.message_tokens + ai_app_messages.answer_tokens) as total_tokens')
            ->selectRaw('COUNT(*) as total_messages')
            ->selectRaw('SUM((ai_app_messages.message_tokens * COALESCE(ai_app_messages.message_price_unit, 0)) + (ai_app_messages.answer_tokens * COALESCE(ai_app_messages.answer_price_unit, 0))) as total_cost_usd')
            ->selectRaw('AVG(ai_app_messages.usd_to_jpy) as avg_usd_to_jpy')
            ->groupBy('ai_app_messages.from_account_id', 'users.first_name', 'users.last_name', 'users.email', 'period')
            ->orderBy('period', 'asc')
            ->orderBy('users.first_name', 'asc')
            ->get();

        $userStatistics = [];
        foreach ($results as $item) {
            $userId = $item->user_id;

            if (!isset($userStatistics[$userId])) {
                $userStatistics[$userId] = [
                    'user_id' => $userId,
                    'user_name' => $item->user_name,
                    'user_email' => $item->user_email,
                    'periods' => [],
                    'total_input_tokens' => 0,
                    'total_output_tokens' => 0,
                    'total_tokens' => 0,
                    'total_messages' => 0,
                    'total_cost_usd' => 0,
                    'total_cost_jpy' => 0,
                ];
            }

            $costJpy = $item->avg_usd_to_jpy ? (float) $item->total_cost_usd * $item->avg_usd_to_jpy : null;

            $userStatistics[$userId]['periods'][] = [
                'period' => $item->period,
                'total_input_tokens' => (int) $item->total_input_tokens,
                'total_output_tokens' => (int) $item->total_output_tokens,
                'total_tokens' => (int) $item->total_tokens,
                'total_messages' => (int) $item->total_messages,
                'total_cost_usd' => (float) $item->total_cost_usd,
                'total_cost_jpy' => $costJpy,
            ];

            $userStatistics[$userId]['total_input_tokens'] += (int) $item->total_input_tokens;
            $userStatistics[$userId]['total_output_tokens'] += (int) $item->total_output_tokens;
            $userStatistics[$userId]['total_tokens'] += (int) $item->total_tokens;
            $userStatistics[$userId]['total_messages'] += (int) $item->total_messages;
            $userStatistics[$userId]['total_cost_usd'] += (float) $item->total_cost_usd;
            $userStatistics[$userId]['total_cost_jpy'] += $costJpy ?? 0;
        }

        $userStatistics = array_values($userStatistics);
        usort($userStatistics, fn($a, $b) => $b['total_tokens'] <=> $a['total_tokens']);

        $summary = [
            'total_users' => count($userStatistics),
            'total_input_tokens' => array_sum(array_column($userStatistics, 'total_input_tokens')),
            'total_output_tokens' => array_sum(array_column($userStatistics, 'total_output_tokens')),
            'total_tokens' => array_sum(array_column($userStatistics, 'total_tokens')),
            'total_messages' => array_sum(array_column($userStatistics, 'total_messages')),
            'total_cost_usd' => array_sum(array_column($userStatistics, 'total_cost_usd')),
            'total_cost_jpy' => array_sum(array_column($userStatistics, 'total_cost_jpy')),
        ];

        return [
            'period_type' => $period,
            'group_by' => 'user',
            'user_statistics' => $userStatistics,
            'summary' => $summary,
        ];
    }

    private function getStatisticsGroupedByApp($baseQuery, string $dateFormat, string $period): array
    {
        $results = (clone $baseQuery)
            ->join('ai_applications', 'ai_app_messages.app_id', '=', 'ai_applications.id')
            ->selectRaw('ai_app_messages.app_id')
            ->selectRaw('ai_applications.name as app_name')
            ->selectRaw("DATE_FORMAT(ai_app_messages.created_at, '{$dateFormat}') as period")
            ->selectRaw('SUM(ai_app_messages.message_tokens) as total_input_tokens')
            ->selectRaw('SUM(ai_app_messages.answer_tokens) as total_output_tokens')
            ->selectRaw('SUM(ai_app_messages.message_tokens + ai_app_messages.answer_tokens) as total_tokens')
            ->selectRaw('COUNT(*) as total_messages')
            ->selectRaw('COUNT(DISTINCT ai_app_messages.from_account_id) as total_users')
            ->selectRaw('SUM((ai_app_messages.message_tokens * COALESCE(ai_app_messages.message_price_unit, 0)) + (ai_app_messages.answer_tokens * COALESCE(ai_app_messages.answer_price_unit, 0))) as total_cost_usd')
            ->selectRaw('AVG(ai_app_messages.usd_to_jpy) as avg_usd_to_jpy')
            ->groupBy('ai_app_messages.app_id', 'ai_applications.name', 'period')
            ->orderBy('period', 'asc')
            ->orderBy('ai_applications.name', 'asc')
            ->get();

        $appStatistics = [];
        foreach ($results as $item) {
            $appId = $item->app_id;

            if (!isset($appStatistics[$appId])) {
                $appStatistics[$appId] = [
                    'app_id' => $appId,
                    'app_name' => $item->app_name,
                    'periods' => [],
                    'total_input_tokens' => 0,
                    'total_output_tokens' => 0,
                    'total_tokens' => 0,
                    'total_messages' => 0,
                    'total_users' => 0,
                    'total_cost_usd' => 0,
                    'total_cost_jpy' => 0,
                ];
            }

            $costJpy = $item->avg_usd_to_jpy ? (float) $item->total_cost_usd * $item->avg_usd_to_jpy : null;

            $appStatistics[$appId]['periods'][] = [
                'period' => $item->period,
                'total_input_tokens' => (int) $item->total_input_tokens,
                'total_output_tokens' => (int) $item->total_output_tokens,
                'total_tokens' => (int) $item->total_tokens,
                'total_messages' => (int) $item->total_messages,
                'total_users' => (int) $item->total_users,
                'total_cost_usd' => (float) $item->total_cost_usd,
                'total_cost_jpy' => $costJpy,
            ];

            $appStatistics[$appId]['total_input_tokens'] += (int) $item->total_input_tokens;
            $appStatistics[$appId]['total_output_tokens'] += (int) $item->total_output_tokens;
            $appStatistics[$appId]['total_tokens'] += (int) $item->total_tokens;
            $appStatistics[$appId]['total_messages'] += (int) $item->total_messages;
            $appStatistics[$appId]['total_cost_usd'] += (float) $item->total_cost_usd;
            $appStatistics[$appId]['total_cost_jpy'] += $costJpy ?? 0;
        }

        foreach ($appStatistics as $appId => &$app) {
            $uniqueUsers = (clone $baseQuery)
                ->where('ai_app_messages.app_id', $appId)
                ->distinct('ai_app_messages.from_account_id')
                ->count('ai_app_messages.from_account_id');
            $app['total_users'] = $uniqueUsers;
        }
        unset($app);

        $appStatistics = array_values($appStatistics);
        usort($appStatistics, fn($a, $b) => $b['total_tokens'] <=> $a['total_tokens']);

        $summary = [
            'total_apps' => count($appStatistics),
            'total_input_tokens' => array_sum(array_column($appStatistics, 'total_input_tokens')),
            'total_output_tokens' => array_sum(array_column($appStatistics, 'total_output_tokens')),
            'total_tokens' => array_sum(array_column($appStatistics, 'total_tokens')),
            'total_messages' => array_sum(array_column($appStatistics, 'total_messages')),
            'total_cost_usd' => array_sum(array_column($appStatistics, 'total_cost_usd')),
            'total_cost_jpy' => array_sum(array_column($appStatistics, 'total_cost_jpy')),
        ];

        return [
            'period_type' => $period,
            'group_by' => 'app',
            'app_statistics' => $appStatistics,
            'summary' => $summary,
        ];
    }

    public function getUserTokenUsage(GetUserTokenUsageRequestEntity $entity): LengthAwarePaginator
    {
        $query = User::query()
            ->whereHas('aiAppMessages', function ($q) use ($entity) {
                $q->where('status', 'success');

                if ($entity->app_id !== null) {
                    $q->where('app_id', $entity->app_id);
                }

                if ($entity->start_date) {
                    $q->whereDate('created_at', '>=', $entity->start_date);
                }

                if ($entity->end_date) {
                    $q->whereDate('created_at', '<=', $entity->end_date);
                }
            });

        if ($entity->search) {
            $searchTerm = $entity->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->paginate($entity->limit, ['*'], 'page', $entity->page);

        $messageConditions = function ($q) use ($entity) {
            $q->where('status', 'success');

            if ($entity->app_id !== null) {
                $q->where('app_id', $entity->app_id);
            }

            if ($entity->start_date) {
                $q->whereDate('created_at', '>=', $entity->start_date);
            }

            if ($entity->end_date) {
                $q->whereDate('created_at', '<=', $entity->end_date);
            }
        };

        $users->getCollection()->transform(function ($user) use ($messageConditions) {
            $messages = $user->aiAppMessages()
                ->where($messageConditions)
                ->with(['application.provider'])
                ->get();

            $lastUsed = $messages->max('created_at');

            $totalTokenUsage = 0;
            $totalCostUsd = 0.0;
            $totalCostJpy = 0.0;

            $appUsage = [];
            foreach ($messages as $message) {
                $appId = $message->app_id;

                if (!isset($appUsage[$appId])) {
                    $application = $message->application;
                    $provider = $application?->provider;

                    $appUsage[$appId] = [
                        'id' => $appId,
                        'name' => $application?->name ?? 'Unknown',
                        'provider' => [
                            'name' => $provider?->name ?? 'Unknown',
                            'provider' => $provider?->provider ?? 'unknown',
                        ],
                        'model' => $application?->model ?? 'unknown',
                        'token_usage' => 0,
                        'cost_usd' => 0.0,
                        'cost_jpy' => 0.0,
                    ];
                }

                $tokens = ($message->message_tokens ?? 0) + ($message->answer_tokens ?? 0);
                $costUsd = (($message->message_tokens ?? 0) * ($message->message_price_unit ?? 0))
                    + (($message->answer_tokens ?? 0) * ($message->answer_price_unit ?? 0));
                $costJpy = $message->usd_to_jpy ? $costUsd * $message->usd_to_jpy : 0;

                $appUsage[$appId]['token_usage'] += $tokens;
                $appUsage[$appId]['cost_usd'] += $costUsd;
                $appUsage[$appId]['cost_jpy'] += $costJpy;

                $totalTokenUsage += $tokens;
                $totalCostUsd += $costUsd;
                $totalCostJpy += $costJpy;
            }

            return [
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'last_used' => $lastUsed,
                'total_token_usage' => $totalTokenUsage,
                'total_cost_usd' => round($totalCostUsd, 6),
                'total_cost_jpy' => round($totalCostJpy, 2),
                'applications' => array_values($appUsage),
            ];
        });

        return $users;
    }

    public function getUserConversations(GetUserConversationsRequestEntity $entity): LengthAwarePaginator
    {
        $conversations = $this->aiMessageConversation
            ->where('app_id', $entity->app_id)
            ->where('from_account_id', $entity->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($entity->limit, ['*'], 'page', $entity->page);

        $conversations->getCollection()->transform(function ($conversation) {
            $messages = $this->aiAppMessage
                ->select([
                    'id',
                    'app_id',
                    'conversation_id',
                    'message',
                    'answer',
                    'message_tokens',
                    'answer_tokens',
                    'message_price_unit',
                    'answer_price_unit',
                    'usd_to_jpy',
                    'status',
                    'error_message',
                    'model_provider',
                    'model_id',
                    'created_at',
                    'generated_files',
                    'reasoning_steps',
                    'response_metadata',
                ])
                ->with(['attachments' => function ($query) {
                    $query->select('id', 'ai_message_id', 'original_name', 'file_path', 'mime_type', 'file_size');
                }])
                ->where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get();

            return [
                'id' => $conversation->id,
                'created_at' => $conversation->created_at?->format('Y/m/d H:i'),
                'from_source' => $conversation->from_source,
                'app_id' => $conversation->app_id,
                'messages' => $messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'app_id' => $message->app_id,
                        'conversation_id' => $message->conversation_id,
                        'message' => $message->message,
                        'answer' => $message->answer,
                        'message_tokens' => $message->message_tokens,
                        'answer_tokens' => $message->answer_tokens,
                        'message_price_unit' => $message->message_price_unit,
                        'answer_price_unit' => $message->answer_price_unit,
                        'usd_to_jpy' => $message->usd_to_jpy,
                        'status' => $message->status,
                        'error_message' => $message->error_message,
                        'model_provider' => $message->model_provider,
                        'model_id' => $message->model_id,
                        'created_at' => $message->created_at?->format('Y/m/d H:i:s'),
                        'attachments' => $message->attachments->map(function ($attachment) {
                            return [
                                'id' => $attachment->id,
                                'ai_message_id' => $attachment->ai_message_id,
                                'original_name' => $attachment->original_name,
                                'file_path' => $attachment->file_path,
                                'mime_type' => $attachment->mime_type,
                                'file_size' => $attachment->file_size,
                                'file_url' => $attachment->file_url,
                            ];
                        }),
                        'generated_files' => $this->transformGeneratedFilesUrl($message->generated_files ?? []),
                        'reasoning_steps' => $message->reasoning_steps ?? [],
                        'total_iterations' => $message->response_metadata['tool_iterations'] ?? 0,
                    ];
                }),
            ];
        });

        return $conversations;
    }

    public function getAppTokenUsage(GetAppTokenUsageRequestEntity $entity): LengthAwarePaginator
    {
        $query = $this->aiApplication
            ->whereHas('messages', function ($q) use ($entity) {
                $q->where('status', 'success');

                if ($entity->start_date) {
                    $q->whereDate('created_at', '>=', $entity->start_date);
                }

                if ($entity->end_date) {
                    $q->whereDate('created_at', '<=', $entity->end_date);
                }
            })
            ->with('provider');

        if ($entity->search) {
            $searchTerm = $entity->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('model', 'like', "%{$searchTerm}%");
            });
        }

        if ($entity->provider) {
            $query->whereHas('provider', function ($q) use ($entity) {
                $q->where('provider', $entity->provider);
            });
        }

        $applications = $query->paginate($entity->limit, ['*'], 'page', $entity->page);

        $messageConditions = function ($q) use ($entity) {
            $q->where('status', 'success');

            if ($entity->start_date) {
                $q->whereDate('created_at', '>=', $entity->start_date);
            }

            if ($entity->end_date) {
                $q->whereDate('created_at', '<=', $entity->end_date);
            }
        };

        $applications->getCollection()->transform(function ($app) use ($messageConditions) {
            $messages = $this->aiAppMessage
                ->where('app_id', $app->id)
                ->where($messageConditions)
                ->get();

            $totalTokenUsage = 0;
            $totalCostUsd = 0.0;
            $totalCostJpy = 0.0;

            $userUsage = [];
            foreach ($messages as $message) {
                $userId = $message->from_account_id;

                if (!isset($userUsage[$userId])) {
                    $user = User::find($userId);
                    $userUsage[$userId] = [
                        'id' => $userId,
                        'name' => $user?->full_name ?? 'Unknown',
                        'email' => $user?->email ?? '',
                        'total_token_usage' => 0,
                        'total_cost_usd' => 0.0,
                        'total_cost_jpy' => 0.0,
                        'last_used' => null,
                    ];
                }

                $tokens = ($message->message_tokens ?? 0) + ($message->answer_tokens ?? 0);
                $costUsd = (($message->message_tokens ?? 0) * ($message->message_price_unit ?? 0))
                    + (($message->answer_tokens ?? 0) * ($message->answer_price_unit ?? 0));
                $costJpy = $message->usd_to_jpy ? $costUsd * $message->usd_to_jpy : 0;

                $userUsage[$userId]['total_token_usage'] += $tokens;
                $userUsage[$userId]['total_cost_usd'] += $costUsd;
                $userUsage[$userId]['total_cost_jpy'] += $costJpy;

                $messageTime = $message->created_at;
                if ($messageTime && (!$userUsage[$userId]['last_used'] || $messageTime > $userUsage[$userId]['last_used'])) {
                    $userUsage[$userId]['last_used'] = $messageTime;
                }

                $totalTokenUsage += $tokens;
                $totalCostUsd += $costUsd;
                $totalCostJpy += $costJpy;
            }

            $users = array_map(function ($user) {
                return [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'total_token_usage' => $user['total_token_usage'],
                    'total_cost_usd' => round($user['total_cost_usd'], 6),
                    'total_cost_jpy' => round($user['total_cost_jpy'], 2),
                    'last_used' => $user['last_used']?->format('Y/m/d H:i:s'),
                ];
            }, array_values($userUsage));

            return [
                'id' => $app->id,
                'name' => $app->name,
                'model_provider' => $app->provider?->name ?? 'Unknown',
                'model_id' => $app->model ?? 'unknown',
                'total_token_usage' => $totalTokenUsage,
                'total_cost_usd' => round($totalCostUsd, 6),
                'total_cost_jpy' => round($totalCostJpy, 2),
                'users' => $users,
            ];
        });

        return $applications;
    }

    public function getDashboardStatistics(): array
    {
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $todayUsage = $this->aiAppMessage
            ->where('created_at', '>=', $todayStart)
            ->selectRaw('COALESCE(SUM(message_tokens + answer_tokens), 0) as total_tokens')
            ->selectRaw('COALESCE(SUM(
                CASE
                    WHEN message_price_unit IS NOT NULL AND answer_price_unit IS NOT NULL
                    THEN (message_tokens * message_price_unit / 1000000) + (answer_tokens * answer_price_unit / 1000000)
                    ELSE 0
                END
            ), 0) as total_cost_usd')
            ->first();

        $monthUsage = $this->aiAppMessage
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<=', $monthEnd)
            ->selectRaw('COALESCE(SUM(message_tokens + answer_tokens), 0) as total_tokens')
            ->selectRaw('COALESCE(SUM(
                CASE
                    WHEN message_price_unit IS NOT NULL AND answer_price_unit IS NOT NULL
                    THEN (message_tokens * message_price_unit / 1000000) + (answer_tokens * answer_price_unit / 1000000)
                    ELSE 0
                END
            ), 0) as total_cost_usd')
            ->first();

        $activeUsersCount = $this->aiAppMessage
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<=', $monthEnd)
            ->whereNotNull('from_account_id')
            ->distinct('from_account_id')
            ->count('from_account_id');

        $topUsers = $this->aiAppMessage
            ->where('ai_app_messages.created_at', '>=', $monthStart)
            ->where('ai_app_messages.created_at', '<=', $monthEnd)
            ->whereNotNull('from_account_id')
            ->join('users', 'ai_app_messages.from_account_id', '=', 'users.id')
            ->selectRaw("users.id, CONCAT(users.first_name, ' ', users.last_name) as name, users.email")
            ->selectRaw('SUM(message_tokens + answer_tokens) as total_tokens')
            ->selectRaw('SUM(
                CASE
                    WHEN message_price_unit IS NOT NULL AND answer_price_unit IS NOT NULL
                    THEN (message_tokens * message_price_unit / 1000000) + (answer_tokens * answer_price_unit / 1000000)
                    ELSE 0
                END
            ) as total_cost_usd')
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderByDesc('total_tokens')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'tokens' => (int) $user->total_tokens,
                    'cost_usd' => round((float) $user->total_cost_usd, 6),
                ];
            })
            ->toArray();

        $modelUsage = $this->aiAppMessage
            ->where('ai_app_messages.created_at', '>=', $monthStart)
            ->where('ai_app_messages.created_at', '<=', $monthEnd)
            ->join('ai_applications', 'ai_app_messages.app_id', '=', 'ai_applications.id')
            ->selectRaw('ai_applications.model')
            ->selectRaw('SUM(message_tokens + answer_tokens) as total_tokens')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('ai_applications.model')
            ->orderByDesc('total_tokens')
            ->limit(5)
            ->get();

        $totalModelTokens = $modelUsage->sum('total_tokens');
        $modelUsageData = $modelUsage->map(function ($model) use ($totalModelTokens) {
            $percentage = $totalModelTokens > 0
                ? round(($model->total_tokens / $totalModelTokens) * 100, 1)
                : 0;
            return [
                'model' => $model->model ?? 'Unknown',
                'tokens' => (int) $model->total_tokens,
                'message_count' => (int) $model->message_count,
                'percentage' => $percentage,
            ];
        })->toArray();

        $latestRate = \App\Models\CurrencyExchangeRate::getLatestRate('USD', 'JPY');
        $todayCostJpy = round((float) $todayUsage->total_cost_usd * $latestRate, 0);
        $monthCostJpy = round((float) $monthUsage->total_cost_usd * $latestRate, 0);

        return [
            'today' => [
                'tokens' => (int) $todayUsage->total_tokens,
                'cost_usd' => round((float) $todayUsage->total_cost_usd, 6),
                'cost_jpy' => $todayCostJpy,
            ],
            'month' => [
                'tokens' => (int) $monthUsage->total_tokens,
                'cost_usd' => round((float) $monthUsage->total_cost_usd, 6),
                'cost_jpy' => $monthCostJpy,
            ],
            'active_users_count' => $activeUsersCount,
            'top_users' => $topUsers,
            'model_usage' => $modelUsageData,
            'primary_model' => !empty($modelUsageData) ? $modelUsageData[0]['model'] : null,
        ];
    }

    private function transformGeneratedFilesUrl(array $files): array
    {
        return array_map(function ($file) {
            if (isset($file['file_url']) && !str_starts_with($file['file_url'], 'http')) {
                $file['file_url'] = asset($file['file_url']);
            }
            return $file;
        }, $files);
    }
}
