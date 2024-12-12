<?php

namespace Domain\Country\Models;

use Domain\Country\Factories\CountryFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'region',
        'timezones',
        'iso_alpha_2',
        'iso_alpha_3',
        'iso_numeric',
        'phone',
        'emoji',
        'image',
    ];

    protected $casts = [
        'timezones' => 'array',
    ];

    protected static function newFactory(): CountryFactory
    {
        return new CountryFactory();
    }
}
