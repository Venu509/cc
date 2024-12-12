<?php

namespace Domain\Notification\Actions;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Domain\Notification\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Domain\Notification\Resources\NotificationResources;

class ListNotificationsAction
{
    public function execute(
        ?bool $isApi = false,
        ?array $params = [],
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $user = $params['user'] ?? null;
        $userType = $params['userType'] ?? null;
        $domain = $params['domain'] ?? null;
        $isRead = $params['isRead'] ?? null;

        return Notification::query()
            ->latest('created_at')
            ->when($user, function (Builder $builder) use ($user) {
                return $builder->where('user_id', $user);
            })->when($userType, function (Builder $builder) use ($userType) {
                return $builder->where('user_type', $userType);
            })->when($domain, function (Builder $builder) use ($domain) {
                return $builder->where('domain', $domain);
            })->when($search, function (Builder $builder) use ($search) {
                return $builder->where('title', 'LIKE', "%$search%");
            })->when($isRead, function (Builder $builder) use ($isRead) {
                return $builder->where('is_read', $isRead);
            })
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($notification) {
                return NotificationResources::make($notification);
            });
    }
}
