<?php

namespace Domain\Vacancy\Models;

use App\Models\User;
use Domain\Category\Models\Category;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\Skill\Models\Skill;
use Domain\Vacancy\Factories\VacancyFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Support\Database\Model;

class Vacancy extends Model
{
    use HasSlug;
    use SoftDeletes;

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(100);
    }

    protected $fillable = [
        'title',
        'slug',
        'salary',
        'description',
        'years_of_experiences',
        'qualifications',
        'benefits',
        'work_modes',
        'no_of_openings',
        'location',
        'expire_date',
        'is_active',
        'is_walk_in_interview',
        'start_date',
        'end_date',
        'category_id',
        'company_id',
        'modified_by',
        'added_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_walk_in_interview' => 'boolean',
        'created_at' => 'date:Y-m-d H:i a',
        'updated_at' => 'date:Y-m-d H:i a',
        'deleted_at' => 'date:Y-m-d H:i a',
    ];

    protected static function newFactory(): VacancyFactory
    {
        return new VacancyFactory();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id')->withTrashed();
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by')->withTrashed();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->with(['parent', 'children'])->withTrashed();
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by')->withTrashed();
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTrashed();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(VacancyQuestion::class, 'vacancy_id')->with(['answer']);
    }

    public function appliedJobs(): HasMany
    {
        return $this->hasMany(UserVacancy::class, 'vacancy_id');
    }

    public function savedVacancies(): HasMany
    {
        return $this->hasMany(SavedVacancy::class, 'vacancy_id');
    }

    public function userVacancyForLoggedUser(): HasOne
    {
        return $this->hasOne(UserVacancy::class)->where('candidate_id', auth()->id());
    }

    public function fileColumnNames(): array
    {
        return [];
    }
}
