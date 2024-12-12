<?php

namespace Domain\Lead\Actions;

use Domain\Lead\Models\Lead;
use Domain\Lead\Resources\LeadResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListLeadsAction
{
    public function execute(
        ?array $params = [],
        ?bool  $isFront = false
    ): LengthAwarePaginator
    {
        $search = $params['search'] ?? null;
        $type = $params['type'] ?? null;

        return Lead::query()
            ->with(['modifiedBy', 'addedBy'])
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($queryBuilder) use ($search) {
                    return $queryBuilder->where('title', 'LIKE', "%$search%")
                        ->orWhere('type', 'LIKE', "%$search%")
                        ->orWhere('description', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%");
                });
            })
            ->where('added_by', auth()->user()->id)
            ->when($isFront, function (Builder $builder) {
                return $builder->where('is_active', true);
            })
            ->when($type && $type !== 'all', function (Builder $builder) use ($type) {
                return $builder->where('type',  $type);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($lead) {
                return new LeadResources($lead);
            });
    }
}