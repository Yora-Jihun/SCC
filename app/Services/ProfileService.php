<?php


namespace App\Services;

use App\DTO\UpdateProfileData;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $user, UpdateProfileData $data): User
    {
        $user->name = $data->name;
        $user->bio = $data->bio;

        if ($data->avatar) {
            $this->replaceAvatar($user, $data->avatar);
        }

        $user->save();

        return $user;
    }

    protected function replaceAvatar(User $user, $avatar): void
    {
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = $avatar->store('avatars', 'public');
    }
}