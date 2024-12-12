<?php

namespace Domain\Job\Models;

use Support\Database\Model;

class VacancyAnswer extends Model
{

    protected $fillable = [
        'vacancy_id',
        'candidate_id',
        'vacancy_question_id',
        'answer',
    ];
}
