<?php

namespace App\Services;

use App\Events\UserFollowed;
use App\Models\User;
use Illuminate\Database\QueryException;

class FollowService
{
    public function follow(User $follower, User $target): bool
    {
        if ($follower->id === $target->id) {
            return false;
        }

        if ($follower->isFollowing($target)) {
            return true; // already following, nothing to do
        }

        try {
            $follower->following()->attach($target->id);
        } catch (QueryException $e) {
            // Same race condition as Chapter 6's LikeService: two rapid
            // clicks both passed the isFollowing() check before either
            // insert completed. The unique constraint rejects the second
            // one; the end state (following = true) is already correct.
        }

        UserFollowed::dispatch($follower, $target);

        return true;
    }

    public function unfollow(User $follower, User $target): void
    {
        $follower->following()->detach($target->id);
    }
}