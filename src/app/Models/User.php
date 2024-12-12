<?php

namespace App\Models;

use Domain\Candidate\Models\WorkExperience;
use Domain\Skill\Models\Skill;
use Domain\User\Enums\ProfileCompletion;
use Domain\User\Models\UserDetails;
use Domain\Vacancy\Models\UserVacancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'current_team_id', 'login_via',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'is_enabled_app_notifications' => 'boolean',
        'is_enabled_push_notifications' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'profile_completion' => 'array',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'modified_by');
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'added_by');
    }

    public function userDetail(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class, 'candidate_id');
    }

    public function appliedJobs(): HasMany
    {
        return $this->hasMany(UserVacancy::class, 'candidate_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function assignRoleWithTeam($role, $team): void
    {
        $this->roles()->attach($role, ['team_id' => $team->id]);
    }

    public function givePermissionToWithTeam($permissionName, $team): void
    {
        $permission = Permission::findByName($permissionName, 'web');
        $this->permissions()->attach($permission->id, ['team_id' => $team->id]);
    }

    public function markStepAsCompleted(ProfileCompletion $step): void
    {
        $completion = $this->profile_completion ?? [];
        $completion[$step->value] = true;
        $this->profile_completion = $completion;
        $this->save();
    }

    public function isStepCompleted(ProfileCompletion $step): bool
    {
        return !empty($this->profile_completion[$step->value]);
    }
    public function getCompletionStatus(): array
    {
        $completion = $this->profile_completion ?? [];
        $status = array_fill_keys(ProfileCompletion::getAllSteps(), false);
        return array_merge($status, $completion);
    }

    public function getCompletionPercentage(): float
    {
        $completedSteps = array_filter($this->profile_completion);
        $totalSteps = count($this->profile_completion);
        $percentage = ($totalSteps > 0) ? (count($completedSteps) / $totalSteps) * 100 : 0;

        return round($percentage / 10) * 10;
    }
}
