<?php

namespace Domain\Vacancy\Models;

use App\Models\User;
use Domain\Job\Casts\ApplicantTrackingCast;
use Domain\Vacancy\Factories\VacancyFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Database\Model;

class UserVacancy extends Model
{
    protected $fillable = [
        'candidate_id',
        'vacancy_id',
    ];

    protected $casts = [
        'history' => ApplicantTrackingCast::class,
    ];

    protected $table = "user_vacancy";

    protected static function newFactory(): VacancyFactory
    {
        return new VacancyFactory();
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function applicantTracking($status, $remarks = null): void
    {
        $history = $this->history ?: [];

        $history[] = [
            'status' => $status,
            'timestamp' => now()->toDateTimeString(),
            'remarks' => $remarks,
            'accessedBy' => auth()->check() ? auth()->user()->name : 'Admin',
            'accessedByID' => auth()->check() ? auth()->user()->id : 1,
        ];

        $this->history = $history;
        $this->save();
        $this->refresh();
    }

}
