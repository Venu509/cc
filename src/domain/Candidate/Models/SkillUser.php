<?php

namespace Domain\Candidate\Models;

use App\Models\User;
use Domain\Skill\Models\Skill;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Support\Database\Model;

class SkillUser extends Model
{
    protected $table = 'skill_user';

    protected $fillable = [
        'skill_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
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
