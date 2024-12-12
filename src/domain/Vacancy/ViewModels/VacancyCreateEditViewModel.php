<?php

namespace Domain\Vacancy\ViewModels;

use App\Models\User;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\Skill\Models\Skill;
use Domain\Vacancy\Models\UserVacancy;
use Domain\Vacancy\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use JsonException;
use Spatie\ViewModels\ViewModel;

class VacancyCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Vacancy $oldVacancy = null
    )
    {
    }

    /**
     * @throws JsonException
     */
    public function vacancy(): array
    {
        $currentRole = auth()->user()->roles()->first()->name;

        $firstUser = auth()->user();

        if($currentRole !== 'company') {
            $firstUser = User::whereHas('roles', static function ($builder) {
                return $builder->where('name', 'company');
            })->first();
        }

        return [
            'id' => $this->oldVacancy?->id,
            'title' => $this->oldVacancy?->title,
            'currentRole' => auth()->user()->roles()->first()->name,
            'slug' => $this->oldVacancy?->slug,
            'salary' => $this->oldVacancy?->salary,
            'yearsOfExperiences' => $this->oldVacancy?->years_of_experiences,
            'parent' => [
                'value' => $this->oldVacancy ? ($this->oldVacancy->category ? $this->oldVacancy->category->parent->id : null) : null,
                'label' => $this->oldVacancy ? ($this->oldVacancy->category ? $this->oldVacancy->category->parent->title : null) : null,
            ],
            'child' => [
                'value' => $this->oldVacancy?->category?->id,
                'label' => $this->oldVacancy?->category?->title,
            ],
            'description' => $this->oldVacancy?->description,
            'qualifications' => $this->oldVacancy?->qualifications ? $this->jsonData($this->oldVacancy?->qualifications) : null,
            'benefits' => $this->oldVacancy?->benefits ? $this->jsonData($this->oldVacancy?->benefits) : null,
            'workModes' => $this->oldVacancy?->work_modes ? $this->jsonData($this->oldVacancy?->work_modes) : null,
            'locations' => $this->oldVacancy?->locations ? $this->jsonData($this->oldVacancy?->locations) : null,
            'noOfOpenings' => $this->oldVacancy->no_of_openings ?? '',
            'expireDate' => $this->oldVacancy->expire_date ?? null,
            'isActive' => $this->oldVacancy?->is_active,
            'appliedJobStatus' => $this->oldVacancy?->userVacancyForLoggedUser?->status,
            'isApplied' => $this->oldVacancy && auth()->check() && UserVacancy::query()->where('candidate_id', auth()->user()->id)->where('vacancy_id', $this->oldVacancy->id)->exists(),
            'isSaved' => $this->oldVacancy && auth()->check() && SavedVacancy::query()->where('candidate_id', auth()->user()->id)->where('vacancy_id', $this->oldVacancy->id)->exists(),
            'salaryFrequency' => $this->oldVacancy?->salary_frequency ?? 'per-annum',
            'company' => [
                'id' => $this->oldVacancy?->company->id,
                'name' => $this->oldVacancy?->company->name,
                'email' => $this->oldVacancy?->company->email,
                'emailVerified' => $this->oldVacancy?->company->email_verified_at !== null,
                'avatar' => imageCheck('user-details/avatars/thumbnail/' .  $this->oldVacancy?->avatar),

                'value' => $this->oldVacancy ? $this->oldVacancy->company->id : ($firstUser->id),
                'label' => $this->oldVacancy ? $this->oldVacancy->company->name : ($firstUser->name),
            ],

            'keySkills' => $this->oldVacancy ? collect($this->oldVacancy->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,

                    'label' => $item->title,
                    'value' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],
            'hasAdditionalQuestions' => (!empty($this->oldVacancy) && count($this->oldVacancy->questions) !== 0) ? 'yes' : 'no',
            'applicationMethod' => $this->oldVacancy->application_method ?? 'internal',
            'externalLink' => $this->oldVacancy?->external_link,
            'additionalQuestions' => $this->oldVacancy ? collect($this->oldVacancy->questions)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'question' => $item->question,
                    'answers' => json_decode($item->answers, true, 512, JSON_THROW_ON_ERROR),
                    'createdAt' => $item->created_at,
                ];
            })->sortBy(function ($item) {
                return $item['createdAt'];
            })->values()->toArray() : [],

            'isWalkInInterview' => $this->oldVacancy ? ($this->oldVacancy->is_walk_in_interview ? 'yes' : 'no') : 'no',
            'startDate' => $this->oldVacancy?->start_date ?? null,
            'endDate' => $this->oldVacancy?->end_date ?? null,
        ];
    }

    public function keySkills(): Collection
    {
        return Skill::query()->select([
            'slug as value',
            'title as label'
        ])->orderBy('title')->get();
    }

    /**
     * @throws JsonException
     */
    private function jsonData($data): array
    {
        return collect(json_decode($data, true, 512, JSON_THROW_ON_ERROR))->map(function ($item) {
            return $item;
        })->toArray();
    }

    public function workModes(): array
    {
        return (new EmployeeHelper)->workModes()->toArray();
    }

    public function locations(): array
    {
        return (new EmployeeHelper)->locations()->toArray();
    }

    public function qualifications()
    {
        return $this->vacancyBuilder()->pluck('qualifications')->map(function ($item) {
            return json_decode($item, true, 512, JSON_THROW_ON_ERROR);
        })
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }

    public function benefits()
    {
        return $this->vacancyBuilder()->pluck('benefits')->map(function ($item) {
            return json_decode($item, true, 512, JSON_THROW_ON_ERROR);
        })
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }

    private function vacancyBuilder(): Builder
    {
        return Vacancy::query();
    }
}
