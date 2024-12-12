<?php

namespace Domain\Banner\Actions;

use Support\Helper\Helper;
use Domain\Banner\Models\Banner;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListBannersAction
{
    use Helper;

    public function execute(
        ?array $params = [],
        ?bool $isFront = false
    ): Collection|LengthAwarePaginator {
        $search = $params['search'] ?? null;

        return Banner::query()
            ->with(['modifiedBy', 'addedBy'])
            ->when($search, function (Builder $query) use ($search) {
                return $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('url', 'LIKE', "%$search%");
            })
            ->when($isFront, function (Builder $builder) {
                return $builder->where('is_active', true);
            })
            ->latest('created_at')
            ->paginate($params['limit'] ?? 10)
            ->withQueryString()
            ->through(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'url' => $banner->url,
                    'image' => imageCheck('banners/thumbnail/'.$banner->image),
                    'isActive' => $banner->is_active,
                    'addedBy' => $banner->addedBy->name,
                    'lastAccessedBy' => $banner->modifiedBy->name,
                ];
            });
    }
}
