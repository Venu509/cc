<?php

namespace Domain\API\Authentication\Models;

use Support\Database\Model;

class OTP extends Model
{
    protected $table = 'otps';

    protected $fillable = [
        'code',
        'phone',
        'email',
        'via',
        'expired_at',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean'
    ];
}
