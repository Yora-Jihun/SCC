<?php
// app/Policies/PostPolicy.php

namespace App\Policies;

use App\Enums\PostVisibility;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function view(User $user, Post $post): bool
    {
        return $post->visibility === PostVisibility::Public || $post->user_id === $user->id;
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }

    public function share(User $user, Post $post): bool
    {
        // Can't share your own post (no reason to), and can't share
        // a private post you don't own — that would leak it to your
        // followers even though the owner marked it private.
        return $post->visibility === PostVisibility::Public && $post->user_id !== $user->id;
    }


    public function like(User $user, Post $post): bool
    {
        // Liking requires the same visibility rule as viewing.
        return $this->view($user, $post);
    }
}