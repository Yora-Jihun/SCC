<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyOtp;

Route::view('/', 'welcome')->name('home');

Route::get('/register', Register::class)->name('register');
Route::get('/verify-otp', VerifyOtp::class)->name('verify-otp');

