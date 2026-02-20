<?php

namespace App\Http\Actions\Api\Users;

use App\Http\Requests\Api\Users\SearchUserRequest;
use App\Domain\Users\UseCase\SearchUserUseCase;
use App\Http\Responders\Api\Users\SearchUserActionResponder;

class SearchUserAction
{
  public function __construct(
    private SearchUserUseCase $useCase,
    private SearchUserActionResponder $responder
  ) {
  }

  public function __invoke(SearchUserRequest $request): \App\Http\Resources\Api\Users\SearchUserActionResource
  {
    $result = $this->useCase->execute($request);
    return $this->responder->__invoke($result);
  }
}
