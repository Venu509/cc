<?php

namespace Domain\User\Actions;

use App\Models\User;
use Domain\User\Resources\UserProfileResources;
use Illuminate\Support\Facades\Log;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUsersAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $role = $params['role'] ?? null;
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

        return User::query()
            ->with(['userDetail', 'skills', 'appliedJobs'])
            ->whereHas('roles', function (Builder $builder) {
                return $builder->whereNotIn('name', ['master', 'super-admin', 'admin']);
            })
            ->when($isAssignedTo, function (Builder $builder) {
                return $builder->where('added_by', auth()->user()->id);
            })
            ->when($tab, function (Builder $builder) use ($vacancyId, $tab) {
                return $builder->whereHas('appliedJobs', function (Builder $builder) use ($vacancyId, $tab) {
                    return $builder->where('vacancy_id', $vacancyId)->whereIn('status', $tab);
                });
            })
            ->when($roles, function (Builder $builder) use ($roles) {
                return $builder->whereHas('roles', function (Builder $builder) use ($roles) {
                    return $builder->whereIn('name', $roles);
                });
            })
            ->when($vacancyId, function (Builder $builder) use ($vacancyId) {
                return $builder->whereHas('appliedJobs', function (Builder $builder) use ($vacancyId) {
                    return $builder->where('vacancy_id', $vacancyId);
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
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%")
                        ->orWhere('alternative_number', 'LIKE', "%$search%");
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
            ->when($locations, function (Builder $builder) use ($locations) {
                return $builder->whereHas('userDetail', function (Builder $builder) use ($locations) {
                    return $builder->where(function (Builder $builder) use ($locations) {
                        return collect($locations)->each(function ($location) use ($builder) {
                            return $builder->orWhere('city', 'LIKE', "%$location%")
                                ->orWhere('state', 'LIKE', "%$location%")
                                ->orWhere('country', 'LIKE', "%$location%");
                        });
                    });
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
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10, ['*'], 'page', $page)
            ->withQueryString()
            ->through(function ($user) use ($vacancyId, $requiredSkills) {
                return UserProfileResources::make($user, $requiredSkills, $vacancyId);
            });
    }
}
