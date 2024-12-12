<?php

namespace Domain\Vacancy\Actions;

use Domain\Vacancy\Models\Vacancy;
use Domain\Vacancy\Resources\VacancyResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListVacanciesAction
{
    public function execute(
        ?array $params = [],
    ): Collection|LengthAwarePaginator {
        $isBelongsToMe = $params['isBelongsToMe'] ?? false;
        $search = $params['search'] ?? null;
        $page = $params['page'] ?? null;
        $keywords = $params['keywords'] ?? null;
        $locations = $params['locations'] ?? null;
        $jobTypes = $params['jobTypes'] ?? null;
        $postedDate = $params['postedDate'] ?? null;
        $appliedJobs = $params['appliedJobs'] ?? false;
        $applicationMethod = $params['applicationMethod'] ?? null;
        $isSavedJobs = $params['isSavedJobs'] ?? false;
        $isMatchedJobs = $params['isMatchedJobs'] ?? false;
        $isApplied = $params['isApplied'] ?? null;
        $tabs = $params['tab'] ?? [];

        $userSkills = [];

        if($isMatchedJobs) {
            $userSkills = array_merge(
                auth()->user()->skills->pluck('title')->toArray(),
                (array)auth()->user()->workExperiences?->pluck('job_title')->toArray(),
                [getStringFromSlug(auth()->user()->userDetail->qualification)],
                [auth()->user()->userDetail->experience],
            );
        }

        return Vacancy::query()
            ->latest('created_at')
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($queryBuilder) use ($search) {
                    return $queryBuilder
                        ->where('title', 'LIKE', "%$search%")
                        ->orWhere('slug', 'LIKE', "%$search%")
                        ->orWhere('salary', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('application_method', 'LIKE', "%$search%");
                });
            })
            ->withCount('appliedJobs')
            ->when($isMatchedJobs, function (Builder $builder) use ($userSkills) {
                return $builder->where(function (Builder $builder) use ($userSkills) {
                    foreach ($userSkills as $skill) {
                        $builder->orWhere(function (Builder $query) use ($skill) {
                            return $query->whereHas('skills', function (Builder $subQuery) use ($skill) {
                                return $subQuery->where('title', 'LIKE', "%$skill%");
                            })
                            ->orWhere('title', 'LIKE', "%$skill%")
                            ->orWhere('description', 'LIKE', "%$skill%");
                        });
                    }
                });
            })
            ->when($applicationMethod, function (Builder $builder) use ($applicationMethod) {
                return $builder->where('application_method', $applicationMethod);
            })
            ->when($isBelongsToMe, function (Builder $builder) {
                return $builder->where('company_id', auth()->user()->id);
            })
            ->when($postedDate, function (Builder $builder) use ($postedDate) {
                return $builder->where('created_at',">=", $postedDate);
            })
            ->when($isSavedJobs, function (Builder $builder) {
                return $builder->whereHas('savedVacancies', function (Builder $builder) {
                    return $builder->where('candidate_id', auth()->user()->id);
                });
            })
            ->when($appliedJobs || $isApplied || $tabs, function (Builder $builder) use ($tabs, $appliedJobs, $isApplied) {
                return $builder->whereHas('appliedJobs', function (Builder $builder) use ($tabs, $appliedJobs, $isApplied) {
                    return $builder->when($appliedJobs, function (Builder $builder) {
                        return $builder->where('candidate_id', auth()->user()->id);
                    })
                    ->when($isApplied && $tabs, function (Builder $builder) use ($tabs) {
                        return $builder->whereIn('status', $tabs);
                    });
                });
            })
            ->when($isApplied, function (Builder $builder) {
                return $builder->whereHas('appliedJobs');
            })
            ->when($keywords, function (Builder $builder) use ($keywords) {
                return $builder->where(function (Builder $builder) use ($keywords) {
                    return $builder->where(function (Builder $builder) use ($keywords) {
                        return collect($keywords)->each(function ($keyword) use ($builder) {
                            $string = getStringFromSlug($keyword);
                            return $builder->orWhere('title', 'LIKE', "%$string%");
                        });
                    });
                });
            })
            ->when($locations, function (Builder $builder) use ($locations) {
                return $builder->where(function (Builder $builder) use ($locations) {
                    foreach ($locations as $location) {
                        $builder->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(locations, '$'))) LIKE ?", ['%' . strtolower($location) . '%']);
                    }
                });
            })
            ->when($jobTypes, function (Builder $builder) use ($jobTypes) {
                return $builder->where(function (Builder $builder) use ($jobTypes) {
                    foreach ($jobTypes as $jobType) {
                        $builder->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(work_modes, '$'))) LIKE ?", ['%' . strtolower($jobType) . '%']);
                    }
                });
            })
            ->paginate($params['limit'] ?? 20, ['*'], 'page', $page)
            ->withQueryString()
            ->through(function ($vacancy) use ($tabs) {
                return VacancyResources::make($vacancy, $tabs);
            });
    }
}
