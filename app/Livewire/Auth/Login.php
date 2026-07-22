<?php
// app/Livewire/Auth/Login.php

namespace App\Livewire\Auth;

use App\Services\Auth\AuthServiceContract;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public string $error = '';

    public function login(AuthServiceContract $authService)
    {
        $this->validate();

        try {
            $authService->attemptLogin($this->email, $this->password, $this->remember);
        } catch (ValidationException $e) {
            $this->error = $e->validator->errors()->first();
            return;
        }

        session()->regenerate();

        return redirect()->route('newsfeed');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}