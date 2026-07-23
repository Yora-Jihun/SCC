<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FollowQuery
{
    public function followersOf(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->followers()->paginate($perPage);
    }

    public function followingOf(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->following()->paginate($perPage);
    }
}