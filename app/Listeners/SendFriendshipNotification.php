<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Notifications\FriendshipFormed;

class SendFriendshipNotification
{
    public function handle(UserFollowed $event): void
    {
        $follower = $event->follower;
        $following = $event->following;

        // At this point the follow that just happened is $follower -> $following.
        // If $following was ALREADY following $follower beforehand, this new
        // follow just completed a mutual pair — they're friends as of right now.
        if ($following->isFollowing($follower)) {
            $follower->notify(new FriendshipFormed($following));
            $following->notify(new FriendshipFormed($follower));
        }
    }
}