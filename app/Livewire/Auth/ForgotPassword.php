<?php
// app/Livewire/Auth/ForgotPassword.php

namespace App\Livewire\Auth;

use App\Services\Auth\AuthServiceContract;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public function send(AuthServiceContract $authService)
    {
        $this->validate();

        $authService->sendPasswordResetOtp($this->email);

        session(['reset_email' => $this->email]);

        return redirect()->route('reset-password');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}