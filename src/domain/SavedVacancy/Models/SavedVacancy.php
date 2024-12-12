<?php

namespace Domain\SavedVacancy\Models;

use App\Models\User;
use Domain\Student\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Database\Model;

class SavedVacancy extends Model
{
    protected $table = 'saved_vacancies';

    protected $fillable = [
        'vacancy_id',
        'candidate_id',
    ];

    public function fileColumnNames(): array
    {
        return [];
    }
}
