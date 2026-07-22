<?php
// app/Livewire/Auth/Register.php

namespace App\Livewire\Auth;

use App\DTO\RegisterUserData;
use App\Services\Auth\AuthServiceContract;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register(AuthServiceContract $authService)
    {
        $this->validate();

        $authService->register(new RegisterUserData(
            name: $this->name,
            email: $this->email,
            password: $this->password,
        ));

        session(['otp_email' => $this->email]);

        return redirect()->route('verify-otp');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}