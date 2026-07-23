<?php

namespace App\Livewire\Follow;

use App\Models\User;
use App\Services\FollowService;
use Livewire\Component;

class FollowButton extends Component
{
    public User $profileUser;
    public bool $isFollowing = false;
    public bool $isFriends = false;

    public function mount(User $profileUser): void
    {
        $this->profileUser = $profileUser;
        $this->refreshState();
    }

    public function toggle(FollowService $followService): void
    {
        if ($this->profileUser->id === auth()->id()) {
            return;
        }

        if ($this->isFollowing) {
            $followService->unfollow(auth()->user(), $this->profileUser);
        } else {
            $followService->follow(auth()->user(), $this->profileUser);
        }

        $this->refreshState();
    }

    protected function refreshState(): void
    {
        $viewer = auth()->user();

        $this->isFollowing = $viewer->isFollowing($this->profileUser);
        $this->isFriends = $viewer->isFriendsWith($this->profileUser);
    }

    public function render()
    {
        return view('livewire.follow.follow-button');
    }
}