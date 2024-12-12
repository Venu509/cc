<?php

namespace Domain\User\Models;

use Domain\Branch\Models\Branch;
use Domain\User\Factories\UserDetailsFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Database\Model;

class UserDetails extends Model
{
    protected $fillable = [
        'full_name',
        'student_id',
        'gender',
        'address',
        'age',
        'dob',
        'pan_card_number',
        'adhar_card_number',
        'no_of_experiences',
        'years_of_existence',
        'qualification',
        'marital_status',
        'resume',
        'experience',
        'skill_set',
        'username',
        'company_name',
        'company_mobile_number',
        'company_email',
        'contact_person',
        'contact_person_email',
        'contact_person_phone',
        'contact_person_address',
        'company_url',
        'company_logo',
        'registration_doc',
        'full_name',
        'dob',
        'age',
        'gender',
        'marital_status',
        'street',
        'city',
        'state',
        'postal_code',
        'country',
        'registered_at',
        'user_id',
    ];

    protected $casts = [
        'can_relocated' => 'boolean'
    ];

    protected static function newFactory(): UserDetailsFactory
    {
        return new UserDetailsFactory();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
