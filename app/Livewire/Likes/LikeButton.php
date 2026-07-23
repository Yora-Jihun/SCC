<?php
// app/Livewire/Likes/LikeButton.php

namespace App\Livewire\Likes;

use App\Models\Post;
use App\Services\LikeService;
use Livewire\Component;

class LikeButton extends Component
{
    public Post $post;
    public int $count = 0;
    public bool $liked = false;

  public function mount(Post $post): void
{
    $this->post = $post;
    $this->count = $post->likes()->count();
    $this->liked = $post->likes()->where('user_id', auth()->id())->exists();
}

    public function toggle(LikeService $likeService): void
    {
        $this->authorize('like', $this->post);

        $this->liked = $likeService->toggle($this->post, auth()->user());
        $this->count += $this->liked ? 1 : -1;
    }

    public function render()
    {
        return view('livewire.likes.like-button');
    }
}