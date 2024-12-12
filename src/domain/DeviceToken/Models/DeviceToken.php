<?php

namespace Domain\DeviceToken\Models;

use Support\Database\Model;

class DeviceToken extends Model
{
    protected $table = 'device_tokens';

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
    ];
}
