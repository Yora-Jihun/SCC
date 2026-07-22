<?php

namespace App\Livewire\Profile;

use App\DTO\UpdateProfileData;
use App\Services\ProfileService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public ?string $bio = null;
    public $avatar = null; // TemporaryUploadedFile while a new photo is selected

    public string $message = '';

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->bio = auth()->user()->bio;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:' . config('profile.avatar.max_kb')
                . '|mimes:' . implode(',', config('profile.avatar.mimes')),
        ];
    }

    public function save(ProfileService $profileService): void
    {
        $this->validate();

        $profileService->updateProfile(auth()->user(), new UpdateProfileData(
            name: $this->name,
            bio: $this->bio,
            avatar: $this->avatar,
        ));

        $this->avatar = null;
        $this->message = 'Profile updated!';
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}