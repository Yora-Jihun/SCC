<?php

namespace App\DTO;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateProfileData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $bio,
        public readonly ?TemporaryUploadedFile $avatar = null,
    ) {}
}