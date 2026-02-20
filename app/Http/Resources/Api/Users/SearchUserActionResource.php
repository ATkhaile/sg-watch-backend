<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Components\CommonComponent;

class SearchUserActionResource extends JsonResource
{
  public function toArray($request): array
  {
    $users = collect($this->resource['data']['users'] ?? [])->map(function ($user) {
      $avatarUrl = CommonComponent::getFullUrl($user->avatar_url);

      return [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'name' => $user->full_name,
        'avatar' => $avatarUrl,
        'is_following' => (bool) $user->is_following,
      ];
    })->toArray();

    return [
      'success' => true,
      'message' => __('users.search.success'),
      'status_code' => 200,
      'data' => [
        'users' => $users,
        'pagination' => $this->resource['data']['pagination'] ?? [],
      ],
    ];
  }
}
