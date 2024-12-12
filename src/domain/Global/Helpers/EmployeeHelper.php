<?php

namespace Domain\Global\Helpers;

use App\Models\User;
use Domain\API\Authentication\Data\RegisterData;
use Domain\Candidate\Models\SkillUser;
use Domain\Country\Models\Country;
use Domain\Global\Data\EmployeeData;
use Domain\Skill\Models\Skill;
use Domain\User\Models\UserDetails;
use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use JsonException;

class EmployeeHelper
{
    public function employmentTypes(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect([
            [
                'value' => 'all',
                'label' => 'All Employment Types',
            ],
            [
                'label' => getStringFromSlug('full-time'),
                'value' => 'full-time',
            ],
            [
                'label' => getStringFromSlug('permanent'),
                'value' => 'permanent',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function employmentStatus(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect([
            [
                'value' => 'all',
                'label' => 'All Employment Status',
            ],
            [
                'label' => getStringFromSlug('permanent'),
                'value' => 'permanent',
            ],
            [
                'label' => getStringFromSlug('temporary'),
                'value' => 'temporary',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function industries(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect([
            [
                'value' => 'all',
                'label' => 'All Industries',
            ],
            [
                'label' => getStringFromSlug('it'),
                'value' => 'it',
            ],
            [
                'label' => getStringFromSlug('marketing'),
                'value' => 'marketing',
            ],
            [
                'label' => getStringFromSlug('healthcare'),
                'value' => 'healthcare',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function jobTypes(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect(array_merge([
            [
                'value' => 'all',
                'label' => 'All Job Types',
            ],
        ], Vacancy::query()->pluck('work_modes')->map(function ($item) {
            return json_decode($item, true, 512, JSON_THROW_ON_ERROR);
        })
            ->flatten()
            ->unique()
            ->values()
            ->map(function ($mode) {
                return [
                    'value' => strtolower($mode),
                    'label' => ucfirst($mode),
                ];
            })
            ->toArray()));

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function locations(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $jobLocations = collect(array_merge([
            [
                'value' => 'all',
                'label' => 'All Job Locations',
            ],
        ], Vacancy::query()->pluck('locations')->reject(null)->map(function ($item) {
            return json_decode($item, true, 512, JSON_THROW_ON_ERROR);
        })
            ->flatten()
            ->unique()
            ->values()
            ->map(function ($mode) {
                return [
                    'value' => strtolower($mode),
                    'label' => ucfirst($mode),
                ];
            })
            ->toArray()));

        if ($currentRouteName !== 'admin.vacancies.index') {
            $jobLocations = $jobLocations->filter(function ($jobLocations) {
                return $jobLocations['value'] !== 'all';
            })->values();
        }

        return $jobLocations;
    }

    public function userPreferredLocations(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $jobLocations = collect(array_merge([
            [
                'value' => 'all',
                'label' => 'All Locations',
            ],
        ], UserDetails::query()
            ->pluck('job_preferences')
            ->reject(fn($item) => empty($item))
            ->map(function ($item) {
                $decoded = json_decode($item, true, 512, JSON_THROW_ON_ERROR);
                return $decoded['Location'] ?? [];
            })
            ->flatten(1)
            ->unique('value')
            ->values()
            ->map(function ($location) {
                return [
                    'value' => strtolower($location['value']),
                    'label' => ucfirst($location['label']),
                ];
            })
            ->toArray()));

        if ($currentRouteName !== 'admin.vacancies.index') {
            $jobLocations = $jobLocations->filter(function ($jobLocations) {
                return $jobLocations['value'] !== 'all';
            })->values();
        }

        return $jobLocations;
    }

    public function countries(?array $params = []): Collection
    {
        $search = $params['search'] ?? null;
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect(array_merge([
            [
                'value' => 'all',
                'label' => 'All Countries',
            ],
        ], Country::query()->select([
            'code as value',
            'name as label'
        ])->when($search, function (Builder $builder) use ($search) {
            return $builder->where(function($queryBuilder) use ($search) {
                return $queryBuilder
                    ->where('code', 'LIKE', "%$search%")
                    ->orWhere('name', 'LIKE', "%$search%")
                    ->orWhere('region', 'LIKE', "%$search%")
                    ->orWhere('timezones', 'LIKE', "%$search%")
                    ->orWhere('isoNumeric', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%");
            });
        })->orderBy('name')->get()->toArray()));

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function keySkills(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect(array_merge([
            [
                'value' => 'all',
                'label' => 'All Skills',
            ],
        ], Skill::query()->select([
            'slug as value',
            'title as label'
        ])->orderBy('title')->get()->toArray()));

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function noticePeriods(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noticePeriods = collect([
            [
                'value' => 'all',
                'label' => 'All Notice Periods',
            ],
            [
                'label' => getStringFromSlug('immediate'),
                'value' => 'immediate',
            ],
            [
                'label' => getStringFromSlug('15-days'),
                'value' => '15-days',
            ],
            [
                'label' => getStringFromSlug('30-days'),
                'value' => '30-days',
            ],
            [
                'label' => getStringFromSlug('60-days'),
                'value' => '60-days',
            ],
            [
                'label' => getStringFromSlug('90-days'),
                'value' => '90-days',
            ],
            [
                'label' => getStringFromSlug('more'),
                'value' => 'more',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noticePeriods = $noticePeriods->filter(function ($noticePeriod) {
                return $noticePeriod['value'] !== 'all';
            })->values();
        }

        return $noticePeriods;
    }

    public function noOfExperiences(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noOfExperiences = collect([
            [
                'value' => 'all',
                'label' => 'All No Of Experiences',
            ],
            [
                'label' => getStringFromSlug('no-experience'),
                'value' => 'no-experience',
            ],
            [
                'label' => getStringFromSlug('less-than-1-year'),
                'value' => 'less-than-1-year',
            ],
            [
                'label' => getStringFromSlug('1-to-3-years'),
                'value' => '1-3-years',
            ],
            [
                'label' => getStringFromSlug('3-to-5-years'),
                'value' => '3-5-years',
            ],
            [
                'label' => getStringFromSlug('5-years'),
                'value' => '5-years',
            ],
            [
                'label' => getStringFromSlug('10-years'),
                'value' => '10-years',
            ],
            [
                'label' => getStringFromSlug('10+-years'),
                'value' => '10+-years',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noOfExperiences = $noOfExperiences->filter(function ($noOfExperience) {
                return $noOfExperience['value'] !== 'all';
            })->values();
        }

        return $noOfExperiences;
    }

    public function workModes(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $workModes = collect([
            [
                'value' => 'all',
                'label' => 'All Work Modes',
            ],
            [
                'label' => getStringFromSlug('permanent'),
                'value' => 'permanent',
            ],
            [
                'label' => getStringFromSlug('contract'),
                'value' => 'contract',
            ],
            [
                'label' => getStringFromSlug('Freelancing'),
                'value' => 'Freelancing',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $workModes = $workModes->filter(function ($noOfExperience) {
                return $noOfExperience['value'] !== 'all';
            })->values();
        }

        return $workModes;
    }

    public function qualifications(): Collection
    {
        $currentRouteName = Route::currentRouteName();

        $noOfExperiences = collect([
            [
                'value' => 'all',
                'label' => 'All Qualification',
            ],
            [
                'label' => getStringFromSlug('high-school'),
                'value' => 'high-school',
            ],
            [
                'label' => getStringFromSlug('diploma'),
                'value' => 'diploma',
            ],
            [
                'label' => getStringFromSlug('bachelors-degree'),
                'value' => 'bachelors-degree',
            ],
            [
                'label' => getStringFromSlug('masters-degree'),
                'value' => 'masters-degree',
            ],
        ]);

        if ($currentRouteName !== 'admin.candidates.index') {
            $noOfExperiences = $noOfExperiences->filter(function ($noOfExperience) {
                return $noOfExperience['value'] !== 'all';
            })->values();
        }

        return $noOfExperiences;
    }

    public function storeKeySkills(RegisterData|EmployeeData $data, User $user): void
    {
        SkillUser::query()->where('user_id', $user->id)->delete();
        collect($data->keySkills)->each(function ($skill) use ($user) {
            $skillUser = new SkillUser();
            $skillBuilder = Skill::query()->where('slug', $skill['value']);

            if(!$skillBuilder->exists()) {

                $keySkill = $this->createNewSkill(new Skill(), $skill, $user);
                $keySkill->save();
                $keySkill->refresh();
            } else {
                $keySkill = $skillBuilder->first();
            }

            $skillUser = $this->updateCandidateKeySkills($skillUser, $keySkill, $user);

            $skillUser->save();
            $skillUser->refresh();
        });
    }

    protected function updateCandidateKeySkills(SkillUser $skillUser, Skill|Builder $skill, User $user): SkillUser
    {
        $skillUser->forceFill([
            'skill_id' => $skill->id,
            'user_id' => $user->id,
        ]);

        return $skillUser;
    }

    protected function createNewSkill(Skill $skill, array $data, User $user): Skill
    {
        $skill->forceFill([
            'title' => getStringFromSlug($data['value']),
            'slug' => slugGenerator($data['value']),
            'modified_by' => $user->id,
            'added_by' => $user->id,
        ]);

        return $skill;
    }

    public function workExperiences($user): Collection
    {
        $totalMonths = 0;

        $workExperiences = $user->workExperiences ? collect($user->workExperiences)->map(function ($workExperience) use (&$totalMonths) {
            $startDate = Carbon::parse($workExperience->start_date);
            $endDate = $workExperience->is_still_working ? now() : Carbon::parse($workExperience->end_date);

            $monthsDiff = $startDate->diffInMonths($endDate);

            $totalMonths += $monthsDiff;

            return [
                'company' => $workExperience->company,
                'jobTitle' => $workExperience->job_title,
                'startDate' => $workExperience->start_date,
                'endDate' => $workExperience->is_still_working ? null : $workExperience->end_date,
                'isStillWorking' => $workExperience->is_still_working,
                'responsibilities' => $workExperience->responsibilities,
                'achievements' => $workExperience->achievements,
                'otherExperiences' => $workExperience->other_experiences,
                'candidateId' => $workExperience->candidate_id,
                'experienceInMonths' => $monthsDiff,
            ];
        }) : collect([]);

        $totalYears = floor($totalMonths / 12);
        $remainingMonths = $totalMonths % 12;

        return collect([
            'workExperiences' => $workExperiences,
            'totalExperience' => [
                'years' => $totalYears,
                'months' => $remainingMonths,
            ],
        ]);
    }

    public function avatar($user): ?string
    {
        if($user->avatar) {
            return imageCheck('user-details/avatars/thumbnail/' . $user->avatar);
        }

        return $user->profile_photo_path ? imageCheck($user->profile_photo_path) : "https://ui-avatars.com/api/?name=". $user->name . "&color=7F9CF5&background=87e8ff";
    }

    public function calculateSkillMatchPercentage(array $userSkills, array $vacancySkills): int
    {
        $userSkills = array_filter(array_map('trim', $userSkills));
        $vacancySkills = array_filter(array_map('trim', $vacancySkills));

        if (empty($vacancySkills)) {
            return 0;
        }

        $userSkillsLower = array_map('strtolower', $userSkills);
        $vacancySkillsLower = array_map('strtolower', $vacancySkills);

        $exactMatches = array_intersect($userSkillsLower, $vacancySkillsLower);

        $partialMatches = array_filter($vacancySkillsLower, static function($vacancySkill) use ($userSkillsLower) {
            foreach ($userSkillsLower as $userSkill) {
                if (stripos($vacancySkill, $userSkill) !== false || stripos($userSkill, $vacancySkill) !== false) {
                    return true;
                }
            }
            return false;
        });

        $totalMatches = array_unique(array_merge($exactMatches, $partialMatches));

        $matchingPercentage = (count($totalMatches) / count($vacancySkills)) * 100;

        return round($matchingPercentage);
    }

    public function appliedVacancies($user): array|Collection
    {
        return $user->appliedJobs ? collect($user->appliedJobs)->map(function ($job) {
            return VacancyResources::make(findVacancyById($job->vacancy_id));
        }) : [];
    }

    /**
     * @throws JsonException
     */
    public function jobPreferences($user): ?array
    {
        $jobPreferences = $this->getUserDetails($user)?->job_preferences;

        if($jobPreferences) {
            return json_decode($jobPreferences, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }

    private function getUserDetails($user)
    {
        return $user ? $user->userDetail : null;
    }
}