<?php

namespace App\Http\Responders\Api\Users;

use App\Http\Responders\ApiResponse;
use App\Http\Resources\Api\Users\SearchUserActionResource;

class SearchUserActionResponder
{
  public function __invoke(array $result): SearchUserActionResource
  {
    return new SearchUserActionResource($result);
  }
}
