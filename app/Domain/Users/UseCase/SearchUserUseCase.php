<?php

namespace App\Domain\Users\UseCase;

use App\Domain\Users\Entity\UsersEntity;
use App\Domain\Users\Repository\UsersRepository;
use App\Http\Requests\Api\Users\SearchUserRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchUserUseCase
{
  public function __construct(
    private UsersRepository $repository
  ) {
  }

  public function execute(SearchUserRequest $request): array
  {
    $entity = new UsersEntity(
      search: $request->input('keyword'),
      page: $request->input('page', 1),
      limit: $request->input('limit', 10)
    );

    $users = $this->repository->getAllUsers($entity);

    return [
      'status_code' => 200,
      'data' => [
        'users' => $users->items(),
        'pagination' => [
          'current_page' => $users->currentPage(),
          'last_page' => $users->lastPage(),
          'per_page' => $users->perPage(),
          'total' => $users->total(),
        ],
      ],
    ];
  }
}
