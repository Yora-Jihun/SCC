<?php

namespace App\Livewire\Follow;

use App\Models\User;
use App\Queries\FollowQuery;
use Livewire\Component;

class ConnectionsList extends Component
{
    public User $user;
    public string $type; // 'followers' | 'following'

    public function mount(User $user, string $type): void
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function render(FollowQuery $followQuery)
    {
        $people = $this->type === 'followers'
            ? $followQuery->followersOf($this->user)
            : $followQuery->followingOf($this->user);

        return view('livewire.follow.connections-list', ['people' => $people]);
    }
}