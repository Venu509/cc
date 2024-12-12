<?php

namespace Domain\Student\Actions;

use Domain\Student\Models\Student;
use Domain\Student\Resources\StudentResources;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListStudentsAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $isFiltered = $params['isFiltered'] ?? false;
        $filterRestrictRoles = $params['filterRestrictRoles'] ?? null;
        $user = $params['user'] ?? null;

        return Student::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function( Builder $queryBuilder) use ($search) {
                    return $queryBuilder
                        ->where('first_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('student_id', 'LIKE', "%$search%")->orWhere(function (Builder $builder) use ($search) {
                            return $builder->whereHas('branch', function ($query) use ($search) {
                                $query->where('name', 'LIKE', "%$search%");
                            });
                        });
                });
            })
            ->when($isFiltered && !in_array($user->role, [$filterRestrictRoles]), function (Builder $builder) use ($user) {
                return $builder->where('added_by', $user->id);
            })
            ->orderBy('first_name')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($student) {
                return StudentResources::make($student);
            });
    }
}
