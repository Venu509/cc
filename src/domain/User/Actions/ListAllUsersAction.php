<?php

namespace Domain\User\Actions;

use App\Models\User;
use Support\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use Domain\Attendance\Actions\ListAttendancesAction;
use Domain\DailyRoute\Actions\CalculateTotalDistance;

class ListAllUsersAction
{
    use Helper;

    public function execute(
        ?array $params = [],
    ) {
        $search = $params['search'] ?? null;
        $role = $params['role'] ?? null;
        $user = $params['user'] ?? null;
        $date = $params['date'] ?? null;
        $userRole = null;

        $attendanceParams = [
            'user' => $user,
            'date' => $date,
        ];

        $attendance = (new ListAttendancesAction())->execute($attendanceParams)->first();

        $totalDistance = (new CalculateTotalDistance())->execute((int) $params['user'], $date);
        if ($user) {
            $userRole = User::query()->where('id', $user)->first()->roles[0]->id;
        }

        $setting = settings('ta-da', $userRole)->first();

        $topLevelUser = User::query()
            ->with(['roles', 'modifiedBy', 'addedBy', 'region', 'area', 'city', 'children', 'parent'])
            ->whereHas('roles', function (Builder $builder) {
                $builder->whereNotIn('name', ['master', 'super-admin', 'admin', 'customer', 'dealer', 'driver']);
            })
            ->when($user, function (Builder $builder) use ($user) {
                return $builder->where('id', $user);
            })
            ->when($role, function (Builder $builder) use ($role) {
                return $builder->whereHas('roles', function (Builder $builder) use ($role) {
                    $builder->where('name', $role);
                });
            })
            ->when($search, function (Builder $builder) use ($search) {
                return $builder
                    ->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%");
            })
            ->latest('created_at')
            ->first();

        $userList = [];
        $childUsers = [];
        if ($topLevelUser) {
            $this->getAllUsersUnderRegionalManager($topLevelUser, $userList);
            foreach ($userList as $user) {
                $childUsers[] = $user;
            }
        }

        return collect($childUsers)->map(function ($user) use ($setting, $totalDistance, $attendance, $date) {
            $userData = (new ListUsersAction())->userData($user, $date, $attendance, $totalDistance, $setting);

            unset($userData['children']);

            return $userData;
        });
    }

    public function getAllUsersUnderRegionalManager($topLevelUser, &$userList = []): void
    {
        $userList[] = $topLevelUser;
        foreach ($topLevelUser->children as $child) {
            $this->getAllUsersUnderRegionalManager($child, $userList);
        }
    }
}
