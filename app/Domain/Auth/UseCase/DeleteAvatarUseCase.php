<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use Illuminate\Support\Facades\Storage;

final class DeleteAvatarUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(): bool
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        // Delete avatar file if exists
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        // Update database to set avatar to null
        $result = $this->userRepository->deleteAvatar($user->id);

        if (!$result) {
            throw new \Exception(__('Avatar deletion failed'));
        }

        return true;
    }
}
