<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class ShowProfile extends Component
{
    public User $profileUser;
    public string $tab = 'posts'; // 'posts' | 'followers' | 'following'

    public function mount(User $user): void
    {
        $this->profileUser = $user;
    }

    public function showTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.profile.show-profile');
    }
}