<?php

namespace App\Models;

use App\Enums\OtpPurpose;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['email', 'code', 'purpose', 'attempts', 'expires_at'];

    protected $casts = [
        'purpose' => OtpPurpose::class,
        'expires_at' => 'datetime',
    ];

    public function isExpired() : bool
    {
        return $this->expires_at->isPast();
    }
}
