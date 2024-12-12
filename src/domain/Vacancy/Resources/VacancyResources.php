<?php

namespace Domain\Vacancy\Resources;

use Carbon\Carbon;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\SavedVacancy\Models\SavedVacancy;
use Domain\Vacancy\Models\UserVacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use JsonException;

class VacancyResources extends JsonResource
{
    public array $tabs = [];

    public function __construct($resource, ?array $tabs = [])
    {
        parent::__construct($resource);
        $this->tabs = $tabs;
    }

    /**
     * @throws JsonException
     */
    public function toArray(Request $request): array
    {
        $userVacancyBuilder = null;

        if(auth()->user()) {
           $userVacancyBuilder = UserVacancy::query()->where('candidate_id', auth()->user()->id)->where('vacancy_id', $this->id);
        }

        $appliedCandidatesCount = [
            'total' => $this->appliedJobs()->count(),
            'selected' => $this->appliedJobs()->whereIn('status', $this->tabs)->count(),
        ];

        $data = [
            'id' => $this->id,
            'title' => getStringFromSlug($this->title),
            'slug' => $this->slug,
            'salary' => $this->salary,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'title' => $this->category->title,
                'slug' => $this->category->slug,
                'parent' => $this->category->parent ? [
                    'id' => $this->category->parent->id,
                    'title' => $this->category->parent->title,
                    'slug' => $this->category->parent->slug,
                ] : [],
            ] : [],
            'description' => $this->description,
            'qualifications' => $this->qualifications ? json_decode($this->qualifications, true, 512, JSON_THROW_ON_ERROR) : [],
            'benefits' => $this->benefits ? json_decode($this->benefits, true, 512, JSON_THROW_ON_ERROR) : [],
            'workModes' => $this->work_modes ? json_decode($this->work_modes, true, 512, JSON_THROW_ON_ERROR) : [],
            'locations' => $this->locations ? json_decode($this->locations, true, 512, JSON_THROW_ON_ERROR) : [],
            'noOfOpenings' => $this->no_of_openings,
            'expireDate' => $this->expire_date,
            'isActive' => $this->is_active,
            'yearsOfExperiences' => $this->years_of_experiences,
            'company' => $this->company ? [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'email' => $this->company->email,
                'emailVerified' => $this->company->email_verified_at !== null,
                'avatar' => (new EmployeeHelper())->avatar($this->company),
            ] : [],

            'keySkills' => $this->skills ? collect($this->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],

            'createdAt' => Carbon::parse( $this->created_at)->format('d/m/Y'),
            'readableDate' => $this->created_at->diffForHumans(),
            'applicationMethod' => $this->application_method,
            'externalLink' => $this->external_link,
            'isApplied' => auth()->check() && $userVacancyBuilder->exists(),

            'applicantTracking' => $userVacancyBuilder ? [
                'status' => $userVacancyBuilder ? ($userVacancyBuilder->first()?->status) : null,
                'history' => ($userVacancyBuilder && $userVacancyBuilder->first()) ? collect($userVacancyBuilder->first()->history)->map(function ($history) {
                    $accessedBy = findUserById($history['accessedByID']);
                    return [
                        'status' => $history['status'],
                        'remarks' => $history['remarks'],
                        'date' => formattedDateTime($history['timestamp'], 'Y-m-d'),
                        'dateTime' => formattedDateTime($history['timestamp']),
                        'time' => formattedDateTime($history['timestamp'], 'H:i A'),
                        'accessedBy' => [
                            'id' => $accessedBy->id,
                            'name' => $accessedBy->name,
                            'avatar' => (new EmployeeHelper())->avatar($accessedBy),
                        ],
                    ];
                })->sortByDesc(function ($item) {
                    return $item['dateTime'];
                })->groupBy(function ($item) {
                    return $item['date'];
                }) : [],
            ] : null,

            'isSaved' => auth()->check() && SavedVacancy::query()->where('candidate_id', auth()->user()->id)->where('vacancy_id', $this->id)->exists(),
            'questions' => $this->questions ? collect($this->questions)->map(function ($question){
                return [
                    'id' => $question->id,
                    'question' => $question->question
                ];
            }) : [],
            'salaryFrequency' => getStringFromSlug($this->salary_frequency),

            'isWalkInInterview' => $this->is_walk_in_interview ?? 'no',
            'startDate' => $this->start_date ?? null,
            'endDate' => $this->end_date ?? null,
            'appliedCandidatesCount' => $appliedCandidatesCount,
        ];

        if (str_contains(Route::currentRouteName(), '.index')) {
            return array_merge($data, [
                'lastAccessedBy' => $this->modifiedBy->name
            ]);
        }

        return $data;
    }
}
