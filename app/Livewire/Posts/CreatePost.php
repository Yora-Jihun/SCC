<?php
// app/Livewire/Posts/CreatePost.php

namespace App\Livewire\Posts;

use App\DTO\CreatePostData;
use App\Enums\PostVisibility;
use App\Services\PostService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public string $body = '';
    public string $visibility = 'public';
    public $image = null;

    protected function rules(): array
    {
        return [
            'body' => 'nullable|string|max:' . config('posts.max_body_length'),
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:' . config('posts.image.max_kb')
                . '|mimes:' . implode(',', config('posts.image.mimes')),
        ];
    }

    public function save(PostService $postService): void
    {
        $this->validate();

        $postService->createPost(new CreatePostData(
            userId: auth()->id(),
            body: $this->body,
            visibility: PostVisibility::from($this->visibility),
            image: $this->image,
        ));

        $this->reset(['body', 'image']);
        $this->visibility = 'public';

        $this->dispatch('post-created');
    }

    public function render()
    {
        return view('livewire.posts.create-post');
    }
}