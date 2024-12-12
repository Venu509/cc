<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\User\Resources\CandidateResources;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Support\Helper\Helper;

class ListCandidatesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $roles = $params['roles'] ?? null;
        $isAssignedTo = $params['isAssignedTo'] ?? false;
        $type = $params['type'] ?? null;
        $tab = $params['tab'] ?? null;
        $keywords = $params['keywords'] ?? null;
        $locations = $params['locations'] ?? null;
        $qualifications = $params['qualifications'] ?? null;
        $jobTypes = $params['jobTypes'] ?? null;
        $industries = $params['industries'] ?? null;
        $employmentStatus = $params['employmentStatus'] ?? null;
        $numberOfExperiences = $params['numberOfExperiences'] ?? null;
        $noticePeriod = $params['noticePeriod'] ?? null;
        $vacancyId = $params['vacancyId'] ?? null;
        $requiredSkills = $params['requiredSkills'] ?? [];
        $page = $params['page'] ?? null;

        $candidates = User::query()
            ->with(['userDetail', 'skills', 'appliedJobs'])
            ->when($isAssignedTo, function (Builder $builder) {
                return $builder->where('added_by', auth()->user()->id);
            })
            ->when($tab && $vacancyId, function (Builder $builder) use ($vacancyId, $tab) {
                return $builder->whereHas('appliedJobs', function (Builder $builder) use ($vacancyId, $tab) {
                    return $builder->where('vacancy_id', $vacancyId)->whereIn('status', $tab);
                });
            })
            ->when($roles, function (Builder $builder) use ($roles) {
                return $builder->whereHas('roles', function (Builder $builder) use ($roles) {
                    return $builder->whereIn('name', $roles);
                });
            })
            ->when($noticePeriod, function (Builder $builder) use ($noticePeriod) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($noticePeriod) {
                    return $builder->when($noticePeriod !== 'all', function (Builder $builder) use ($noticePeriod) {
                        return $builder->when(is_array($noticePeriod), function (Builder $builder) use ($noticePeriod) {
                            return $builder->whereIn('notice_period', $noticePeriod);
                        })->when(!is_array($noticePeriod), function (Builder $builder) use ($noticePeriod) {
                            return $builder->where('notice_period', $noticePeriod);
                        });
                    });
                });
            })
            ->when($type && $type !== 'all', function (Builder $builder) use ($type) {
                return $builder->whereHas('roles', function (Builder $builder) use ($type) {
                    return $builder->where('name', $type);
                });
            })
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($builderBuilder) use ($search) {
                    return $builderBuilder
                        ->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                });
            })
            ->when($qualifications, function (Builder $builder) use ($qualifications) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($qualifications) {
                    return $builder->whereIn('qualification', $qualifications);
                });
            })
            ->when($employmentStatus, function (Builder $builder) use ($employmentStatus) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($employmentStatus) {
                    return $builder->where(function (Builder $builder) use ($employmentStatus) {
                        foreach ($employmentStatus as $employmentState) {
                            $builder->orWhere('job_preferences->EmploymentStatus->value', $employmentState);
                        }
                    });
                });
            })
            ->when($industries, function (Builder $builder) use ($industries) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($industries) {
                    return $builder->where(function (Builder $builder) use ($industries) {
                        foreach ($industries as $industry) {
                            $builder->orWhere('job_preferences->Industry->value', $industry);
                        }
                    });
                });
            })
            ->when($jobTypes, function (Builder $builder) use ($jobTypes) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($jobTypes) {
                    return $builder->where(function (Builder $builder) use ($jobTypes) {
                        foreach ($jobTypes as $jobType) {
                            $builder->orWhere('job_preferences->Type->value', $jobType);
                        }
                    });
                });
            })
            ->when($numberOfExperiences, function (Builder $builder) use ($numberOfExperiences) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($numberOfExperiences) {
                    return $builder->whereIn('no_of_experiences', $numberOfExperiences);
                });
            })
            ->when($locations, function (Builder $builder) use ($locations) {
                return $builder->where(function (Builder $builder) use ($locations) {
                    $builder->whereHas('userDetail', function (Builder $builder) use ($locations) {
                        return $builder->where(function (Builder $builder) use ($locations) {
                            foreach ($locations as $location) {
                                $builder->orWhereJsonContains('job_preferences->Location', ['label' => getStringFromSlug($location)]);
//                                $builder->orWhereRaw("job_preferences->'$.Location' LIKE ?", ['%' . json_encode(['label' => $location], JSON_THROW_ON_ERROR) . '%']);
                            }
                        });
                    });

                    $builder->orWhereHas('userDetail', function (Builder $builder) use ($locations) {
                        return $builder->where(function (Builder $builder) use ($locations) {
                            foreach ($locations as $location) {
                                $builder->orWhere('city', 'LIKE', "%$location%")
                                    ->orWhere('state', 'LIKE', "%$location%")
                                    ->orWhere('country', 'LIKE', "%$location%");
                            }
                        });
                    });
                });
            })
            ->when($keywords, function (Builder $builder) use ($keywords) {
                return $builder->where(function (Builder $builder) use ($keywords) {
                    $builder->where(function (Builder $builder) use ($keywords) {
                        return collect($keywords)->each(function ($keyword) use ($builder) {
                            return $builder->orWhere('name', 'LIKE', "%$keyword%")
                                ->orWhere('email', 'LIKE', "%$keyword%");
                        });
                    });

                    $builder->orWhereHas('workExperiences', function (Builder $builder) use ($keywords) {
                        collect($keywords)->each(function ($keyword) use ($builder) {
                            return $builder->orWhere('job_title', 'LIKE', "%$keyword%");
                        });
                    });

                    $builder->orWhereHas('userDetail', function (Builder $builder) use ($keywords) {
                        collect($keywords)->each(function ($keyword) use ($builder) {
                            return $builder->orWhere('current_job_title', 'LIKE', "%$keyword%")
                                ->orWhere('skill_set', 'LIKE', "%$keyword%");
                        });
                    });

                    $builder->whereHas('skills', function (Builder $builder) use ($keywords) {
                        return $builder->where(function (Builder $builder) use ($keywords) {
                            return collect($keywords)->each(function ($keyword) use ($builder) {
                                return $builder->orWhere(function (Builder $builder) use ($keyword) {
                                    return $builder->where('title', 'LIKE', "%$keyword%")
                                        ->orWhere('slug', 'LIKE', "%$keyword%");
                                });
                            });
                        });
                    });
                });
            })
            ->get();

        $transformedUsers = $candidates->map(function ($candidate) use ($vacancyId, $requiredSkills) {
            $userSkills = array_merge(
                $candidate->skills->pluck('title')->toArray(),
                (array)$candidate->workExperiences?->pluck('job_title')->toArray(),
                [getStringFromSlug($candidate->userDetail->qualification)],
                [$candidate->userDetail->experience],
                [$candidate->userDetail->additional_qualification],
            );

            $matchedPercentage = (new EmployeeHelper())->calculateSkillMatchPercentage($userSkills, $requiredSkills);
            $noticePeriodValue = $this->getNoticePeriodValue($candidate->userDetail['notice_period']);
            $candidate->setAttribute('matchedPercentage', $matchedPercentage);
            $candidate->setAttribute('noticePeriodValue', $noticePeriodValue);
            return CandidateResources::make($candidate, $requiredSkills, $userSkills,  $vacancyId, $matchedPercentage);
        })->sortByDesc(function (CandidateResources $candidate) {
            return [
                'matchedPercentage' => $candidate->matchedPercentage,
                'noticePeriodValue' => $candidate->noticePeriodValue
            ];
        })->values();

        return $this->paginate($transformedUsers, $params['limit'] ?? 10, $page);
    }

    public function getNoticePeriodValue($noticePeriod): int
    {
        $priority = [
            'immediate' => 6,
            '15-days' => 5,
            '30-days' => 4,
            '60-days' => 3,
            '90-days' => 2,
            'more' => 1
        ];

        return $priority[$noticePeriod] ?? 6;
    }

    public function paginate($items, $perPage = 10, $page = 1): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }
}
