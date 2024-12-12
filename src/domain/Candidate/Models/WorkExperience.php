<?php

namespace Domain\Candidate\Models;

use App\Models\User;
use Domain\Candidate\Factories\WorkExperienceFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Database\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'company',
        'job_title',
        'start_date',
        'end_date',
        'is_still_working',
        'responsibilities',
        'achievements',
        'other_experiences',
        'candidate_id',
    ];

    protected $casts = [
        'is_still_working' => 'boolean'
    ];

    protected static function newFactory(): WorkExperienceFactory
    {
        return new WorkExperienceFactory();
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
}
