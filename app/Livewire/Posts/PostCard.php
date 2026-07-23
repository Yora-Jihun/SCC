<?php

namespace App\Livewire\Posts;

use App\DTO\UpdatePostData;
use App\Enums\PostVisibility;
use App\Models\Post;
use App\Services\PostService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostCard extends Component
{
    use WithFileUploads;

    public Post $post;
    public bool $editing = false;

    public string $body = '';
    public string $visibility = 'public';
    public $image = null;
    public bool $removeImage = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function startEditing(): void
    {
        $this->authorize('update', $this->post);

        $this->body = (string) $this->post->body;
        $this->visibility = $this->post->visibility->value;
        $this->editing = true;
    }

    public function cancelEditing(): void
    {
        $this->editing = false;
        $this->reset(['image', 'removeImage']);
    }

    protected function rules(): array
    {
        return [
            'body' => 'nullable|string|max:' . config('posts.max_body_length'),
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:' . config('posts.image.max_kb')
                . '|mimes:' . implode(',', config('posts.image.mimes')),
        ];
    }

    public function update(PostService $postService): void
    {
        $this->authorize('update', $this->post);
        $this->validate();

        $postService->updatePost($this->post, new UpdatePostData(
            body: $this->body,
            visibility: PostVisibility::from($this->visibility),
            image: $this->image,
            removeImage: $this->removeImage,
        ));

        $this->post->refresh();
        $this->editing = false;
        $this->reset(['image', 'removeImage']);
    }

    public function delete(PostService $postService): void
    {
        $this->authorize('delete', $this->post);

        $postService->deletePost($this->post);

        $this->dispatch('post-deleted');
    }

    public function share(PostService $postService): void
    {
        $this->authorize('share', $this->post);

        $postService->sharePost($this->post, auth()->id());

        $this->dispatch('post-shared');
    }

    public function render()
    {
        return view('livewire.posts.post-card');
    }
}