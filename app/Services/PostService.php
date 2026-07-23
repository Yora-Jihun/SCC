<?php

namespace App\Services;

use App\DTO\CreatePostData;
use App\DTO\UpdatePostData;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function createPost(CreatePostData $data): Post
    {
        return Post::create([
            'user_id' => $data->userId,
            'body' => $data->body,
            'visibility' => $data->visibility,
            'image_path' => $data->image?->store('posts', 'public'),
        ]);
    }

    public function updatePost(Post $post, UpdatePostData $data): Post
    {
        $post->body = $data->body;
        $post->visibility = $data->visibility;

        if ($data->image) {
            $this->replaceImage($post, $data->image);
        } elseif ($data->removeImage) {
            $this->deleteImage($post);
            $post->image_path = null;
        }

        $post->save();

        return $post;
    }

    public function deletePost(Post $post): void
    {
        $this->deleteImage($post);
        $post->delete();
    }

    public function sharePost(Post $original, int $sharerId): Post
    {
        return Post::create([
            'user_id' => $sharerId,
            'shared_post_id' => $original->id,
            'body' => null,
            'visibility' => \App\Enums\PostVisibility::Public,
        ]);
    }

    protected function replaceImage(Post $post, $image): void
    {
        $this->deleteImage($post);
        $post->image_path = $image->store('posts', 'public');
    }

    protected function deleteImage(Post $post): void
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
    }
}