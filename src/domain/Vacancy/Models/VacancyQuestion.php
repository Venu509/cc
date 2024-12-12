<?php

namespace Domain\Vacancy\Models;

use Domain\Job\Models\VacancyAnswer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Database\Model;

class VacancyQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question',
        'answers',
        'vacancy_id',
    ];

    protected $table = 'vacancy_question';

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }

    public function answer(?string $candidateId = null): HasOne
    {
        return $this->hasOne(VacancyAnswer::class, 'vacancy_question_id')
            ->where('candidate_id', $candidateId);
    }
}
