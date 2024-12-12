<?php

namespace Domain\Vacancy\Models;

use Domain\Skill\Models\Skill;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Database\Model;

class VacancySkill extends Model
{
    protected $table = 'skill_vacancy';

    protected $fillable = [
        'skill_id',
        'vacancy_id',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id')->withTrashed();
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id')->withTrashed();
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
