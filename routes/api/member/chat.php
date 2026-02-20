<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Chat Routes
|--------------------------------------------------------------------------
|
| ユーザー間チャット
|
*/

// User to User Chat
Route::get('chat/users-for-chat', \App\Http\Actions\Api\Chat\GetUsersForChatAction::class);
Route::post('chat/message', \App\Http\Actions\Api\Chat\ChatMessageAction::class);
Route::post('chat/messages/mark-as-read', \App\Http\Actions\Api\Chat\MarkMessagesAsReadAction::class);
Route::get('chat/history/list', \App\Http\Actions\Api\Chat\GetAllChatHistoryAction::class);
Route::get('chat/users', \App\Http\Actions\Api\Chat\GetAvailableUsersAction::class);
Route::get('chat/partner/{partnerId}', \App\Http\Actions\Api\Chat\GetChatPartnerAction::class);
Route::get('chat/conversations', \App\Http\Actions\Api\Chat\GetConversationsAction::class);

// Chat Room Presence
Route::post('chat/room/join', \App\Http\Actions\Api\Chat\JoinChatRoomAction::class);
Route::post('chat/room/leave', \App\Http\Actions\Api\Chat\LeaveChatRoomAction::class);
Route::post('chat/typing/start', \App\Http\Actions\Api\Chat\StartTypingAction::class);
Route::post('chat/typing/stop', \App\Http\Actions\Api\Chat\StopTypingAction::class);

// Link Preview
Route::post('chat/link-preview', \App\Http\Actions\Api\Chat\FetchLinkPreviewAction::class);
