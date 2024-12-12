<?php

namespace Domain\API\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Domain\API\Authentication\Factories\InfoUserFactory;

class InfoUser extends Model
{
    use HasFactory;

    protected $table = 'info_user';

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'age',
        'gender',
        'dob',
        'location',
        'height',
        'weight',
        'city',
        'state',
        'country',
        'charge',
        'year_of_experience',
        'degree',
        'avatar',
        'user_id',
    ];

    protected static function newFactory(): InfoUserFactory
    {
        return new InfoUserFactory();
    }
}
