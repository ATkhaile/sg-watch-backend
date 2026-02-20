<?php

namespace App\Domain\Chat\Infrastructure;

use App\Domain\Chat\Entity\ChatMessage;
use App\Domain\Chat\Repository\ChatMessageRepository;
use App\Domain\Chat\Entity\ChatMessageListEntity;
use App\Domain\Chat\Entity\ConversationsListEntity;
use App\Models\ChatMessage as ChatMessageModel;
use App\Models\User;
use App\Models\MessageMention;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Domain\Chat\Entity\UsersEntity;

class DbChatMessageInfrastructure implements ChatMessageRepository
{
    public function create(array $data, ?UploadedFile $file = null): ChatMessage
    {
        // Handle file upload if present
        if ($file) {
            $path = $file->store('chat_files', 'public');
            if ($path) {
                $data['file_url'] = '/storage/' . $path;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_type'] = $file->getClientMimeType();
                $data['file_size'] = $file->getSize();
            }
        }

        // Extract mentioned_user_ids before creating message
        $mentionedUserIds = $data['mentioned_user_ids'] ?? [];
        unset($data['mentioned_user_ids']);

        $chatMessage = ChatMessageModel::create($data);

        // Create mentions if any
        if (!empty($mentionedUserIds)) {
            foreach ($mentionedUserIds as $userId) {
                MessageMention::create([
                    'message_id' => $chatMessage->id,
                    'mentioned_user_id' => $userId,
                ]);
            }
        }

        // Load the message with reply relationships to get complete data
        $messageWithRelations = ChatMessageModel::with([
            'replyToMessage:id,user_id,message,message_type,created_at',
            'replyToMessage.user:id,first_name,last_name'
        ])->find($chatMessage->id);

        $messageData = $messageWithRelations->toArray();

        // Add reply_to_message data if exists
        if ($messageWithRelations->replyToMessage) {
            $messageData['reply_to_message'] = [
                'id' => $messageWithRelations->replyToMessage->id,
                'user_id' => $messageWithRelations->replyToMessage->user_id,
                'user_name' => $messageWithRelations->replyToMessage->user->full_name ?? 'Unknown',
                'message' => $messageWithRelations->replyToMessage->message,
                'message_type' => $messageWithRelations->replyToMessage->message_type,
                'created_at' => $messageWithRelations->replyToMessage->created_at,
            ];
        } else {
            $messageData['reply_to_message'] = null;
        }

        return ChatMessage::fromArray($messageData);
    }

    public function getMessagesBetweenUsers(ChatMessageListEntity $chatMessageListEntity): LengthAwarePaginator
    {
        $userId = $chatMessageListEntity->getUserId();
        $receiverId = $chatMessageListEntity->getReceiverId();
        $limit = $chatMessageListEntity->getLimit();
        $page = $chatMessageListEntity->getPage();

        $query = ChatMessageModel::query()
            ->select(
                'chat_messages.*',
                DB::raw("CONCAT(sender.first_name, ' ', sender.last_name) as sender_name"),
                'sender.avatar_url as sender_avatar',
                DB::raw("CONCAT(receiver.first_name, ' ', receiver.last_name) as receiver_name"),
                'receiver.avatar_url as receiver_avatar'
            )
            ->join('users as sender', 'chat_messages.user_id', '=', 'sender.id')
            ->join('users as receiver', 'chat_messages.receiver_id', '=', 'receiver.id')
            ->with([
                'replyToMessage:id,user_id,message,message_type,created_at',
                'replyToMessage.user:id,first_name,last_name',
                'mentions.mentionedUser:id,first_name,last_name'
            ])
            ->where(function ($q) use ($userId, $receiverId) {
                $q->where('chat_messages.user_id', $userId)->where('chat_messages.receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($userId, $receiverId) {
                $q->where('chat_messages.user_id', $receiverId)->where('chat_messages.receiver_id', $userId);
            });

        return $query->orderBy('chat_messages.created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function markMessagesAsRead(int $userId, int $chatPartnerId): int
    {
        return ChatMessageModel::where('user_id', $chatPartnerId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    public function getUnreadMessagesCount(int $userId, int $chatPartnerId): int
    {
        return ChatMessageModel::where('user_id', $chatPartnerId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getAvailableUsers(\App\Domain\Chat\Entity\AvailableUsersListEntity $entity): LengthAwarePaginator
    {
        $currentUserId = $entity->getUserId();
        $search = $entity->getSearch();
        $limit = $entity->getLimit() ?? 20;
        $page = $entity->getPage() ?? 1;

        $latestMessageSubquery = DB::table('chat_messages')
            ->select('chat_messages.user_id', 'chat_messages.receiver_id', DB::raw('MAX(chat_messages.created_at) as latest_message_at'))
            ->where(function ($q) use ($currentUserId) {
                $q->where('chat_messages.user_id', $currentUserId)
                  ->orWhere('chat_messages.receiver_id', $currentUserId);
            })
            ->groupBy('chat_messages.user_id', 'chat_messages.receiver_id');

        $query = User::where('users.id', '!=', $currentUserId)
            ->leftJoinSub($latestMessageSubquery, 'sent_messages', function ($join) use ($currentUserId) {
                $join->on('users.id', '=', 'sent_messages.receiver_id')
                     ->where('sent_messages.user_id', '=', $currentUserId);
            })
            ->leftJoinSub($latestMessageSubquery, 'received_messages', function ($join) use ($currentUserId) {
                $join->on('users.id', '=', 'received_messages.user_id')
                     ->where('received_messages.receiver_id', '=', $currentUserId);
            })
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.avatar_url as image',
                DB::raw('GREATEST(COALESCE(sent_messages.latest_message_at, \'1970-01-01\'), COALESCE(received_messages.latest_message_at, \'1970-01-01\')) as last_message_at')
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.first_name', 'LIKE', "%{$search}%")
                  ->orWhere('users.last_name', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%");
            });
        }

        $query->orderByRaw('GREATEST(COALESCE(sent_messages.latest_message_at, \'1970-01-01\'), COALESCE(received_messages.latest_message_at, \'1970-01-01\')) DESC')
              ->orderBy('users.first_name', 'ASC');

        return $query->paginate($limit, ['*'], 'page', $page);
    }

    public function getConversations(ConversationsListEntity $entity): array
    {
        $userId = $entity->getUserId();
        $search = $entity->getSearch();
        $messageSearch = $entity->getMessageSearch();
        $limit = $entity->getLimit();
        $offset = ($entity->getPage() - 1) * $limit;

        $conversations = [];

        // Get User Conversations
        $userQuery = User::where('users.id', '!=', $userId)
            ->leftJoin('chat_messages as latest_msg', function ($join) use ($userId) {
                $join->on(DB::raw('(
                    SELECT MAX(id) as max_id
                    FROM chat_messages cm
                    WHERE (
                        (cm.user_id = users.id AND cm.receiver_id = ' . $userId . ') OR
                        (cm.user_id = ' . $userId . ' AND cm.receiver_id = users.id)
                    )
                )'), '=', 'latest_msg.id');
            })
            ->leftJoin('users as msg_sender', 'latest_msg.user_id', '=', 'msg_sender.id')
            ->select(
                DB::raw("'user' as type"),
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.avatar_url',
                'latest_msg.id as latest_message_id',
                'latest_msg.message as latest_message_text',
                'latest_msg.message_type as latest_message_type',
                'latest_msg.file_name as latest_message_file_name',
                'latest_msg.file_type as latest_message_file_type',
                'latest_msg.user_id as latest_message_user_id',
                DB::raw("CONCAT(msg_sender.first_name, ' ', msg_sender.last_name) as latest_message_sender"),
                'latest_msg.created_at as latest_message_created_at',
                'latest_msg.created_at as last_activity'
            );

        // Apply search filter
        if ($search || $messageSearch) {
            $userQuery->where(function ($mainQuery) use ($search, $messageSearch, $userId) {
                if ($search) {
                    $mainQuery->where(function ($q) use ($search) {
                        $q->where('users.first_name', 'LIKE', "%{$search}%")
                          ->orWhere('users.last_name', 'LIKE', "%{$search}%")
                          ->orWhere('users.email', 'LIKE', "%{$search}%");
                    });
                }

                if ($messageSearch) {
                    $mainQuery->orWhereExists(function ($subquery) use ($userId, $messageSearch) {
                        $subquery->select(DB::raw(1))
                            ->from('chat_messages')
                            ->where(function ($q) use ($userId) {
                                $q->where(function ($innerQ) use ($userId) {
                                    $innerQ->where('chat_messages.user_id', $userId)
                                           ->whereRaw('chat_messages.receiver_id = users.id');
                                })->orWhere(function ($innerQ) use ($userId) {
                                    $innerQ->whereRaw('chat_messages.user_id = users.id')
                                           ->where('chat_messages.receiver_id', $userId);
                                });
                            })
                            ->where('chat_messages.message', 'LIKE', "%{$messageSearch}%");
                    });
                }
            });
        }

        $userConversations = $userQuery->get()->map(function ($user) use ($userId) {
            $unreadCount = ChatMessageModel::where('user_id', $user->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $avatarUrl = \App\Components\CommonComponent::getFullUrl($user->avatar_url);

            return [
                'type' => 'user',
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar_url' => $avatarUrl,
                'unread_count' => $unreadCount,
                'latest_message' => $user->latest_message_id ? [
                    'id' => $user->latest_message_id,
                    'message' => $user->latest_message_text,
                    'message_type' => $user->latest_message_type,
                    'file_name' => $user->latest_message_file_name,
                    'file_type' => $user->latest_message_file_type,
                    'file_category' => $user->latest_message_type === 'file'
                        ? \App\Components\CommonComponent::getFileCategory($user->latest_message_file_type)
                        : null,
                    'user_id' => $user->latest_message_user_id,
                    'user_name' => $user->latest_message_sender,
                    'created_at' => $user->latest_message_created_at,
                ] : null,
                'last_activity' => $user->last_activity ?: $user->updated_at,
            ];
        })->toArray();

        $conversations = array_merge($conversations, $userConversations);

        // Sort by last activity (most recent first)
        usort($conversations, function ($a, $b) {
            return strtotime($b['last_activity']) - strtotime($a['last_activity']);
        });

        // Apply pagination
        $total = count($conversations);
        $paginatedConversations = array_slice($conversations, $offset, $limit);

        $totalUnreadCount = array_sum(array_column($conversations, 'unread_count'));

        return [
            'conversations' => $paginatedConversations,
            'total' => $total,
            'current_page' => $entity->getPage(),
            'per_page' => $limit,
            'total_pages' => ceil($total / $limit),
            'total_unread_count' => $totalUnreadCount,
        ];
    }

    public function getAllUsers(UsersEntity $entity): LengthAwarePaginator
    {
        $search = $entity->getSearch();
        $page = $entity->getPage() ?? 1;
        $limit = $entity->getLimit() ?? 10;

        $query = User::where('id', '!=', auth()->id())
            ->select('id', 'first_name', 'last_name', 'email', 'avatar_url as image');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('first_name', 'asc');

        return $query->paginate($limit, ['*'], 'page', $page);
    }
}
