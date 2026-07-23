<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyOtp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

use App\Livewire\Newsfeed;

use App\Livewire\Profile\EditProfile;

use App\Livewire\Profile\ShowProfile;

Route::middleware('guest')->group(function () {
    
    Route::get('/', function (){return view('welcome');})->name('welcome');

    Route::get('/register', Register::class)->name('register');
    Route::get('/verify-otp', VerifyOtp::class)->name('verify-otp');
    Route::get('/login', Login::class)->name('login');
    Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password', ResetPassword::class)->name('reset-password');
});

Route::middleware('auth')->group(function () {
    Route::get('/newsfeed', Newsfeed::class)->name('newsfeed');
    Route::get('/profile/edit', EditProfile::class)->name('profile.edit');
    Route::get('/profile/{user}', ShowProfile::class)->name('profile.show');

});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
