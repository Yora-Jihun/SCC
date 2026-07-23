<?php

namespace App\DTO;

use App\Enums\PostVisibility;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdatePostData
{
    public function __construct(
        public readonly ?string $body,
        public readonly PostVisibility $visibility,
        public readonly ?TemporaryUploadedFile $image = null,
        public readonly bool $removeImage = false,
    ) {}
}