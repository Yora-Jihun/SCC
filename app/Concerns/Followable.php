<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Followable
{
    /**
     * People who follow this user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            static::class,
            'follows',
            'following_id',
            'follower_id'
        )->withTimestamps();
    }

    /**
     * People this user follows.
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            static::class,
            'follows',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }

    public function isFollowing(self $other): bool
    {
        return $this->following()->whereKey($other->id)->exists();
    }

    public function isFollowedBy(self $other): bool
    {
        return $this->followers()->whereKey($other->id)->exists();
    }

    public function isFriendsWith(self $other): bool
    {
        return $this->isFollowing($other) && $other->isFollowing($this);
    }
}