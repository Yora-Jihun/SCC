<?php

namespace App\DTO;

use App\Enums\PostVisibility;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreatePostData
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $body,
        public readonly PostVisibility $visibility,
        public readonly ?TemporaryUploadedFile $image = null,
    ) {}
}