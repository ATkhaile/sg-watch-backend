<?php

namespace App\Domain\Auth\UseCase;

use App\Domain\Auth\Repository\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class UpdateAvatarUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UploadedFile $avatar): array
    {
        $user = auth()->user();

        if (!$user) {
            throw new \Exception(__('auth.notfound'));
        }

        // Delete old avatar if exists
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        // Store new avatar in user-specific directory
        $path = $avatar->store('avatars/' . $user->id, 'public');

        // Update avatar in database
        $result = $this->userRepository->updateAvatar($user->id, $path);

        if (!$result) {
            throw new \Exception(__('Avatar update failed'));
        }

        return [
            'avatar_url' => Storage::disk('public')->url($path),
            'path' => $path
        ];
    }
}
