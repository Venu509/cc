<?php

namespace Domain\Category\Actions;

use Domain\Category\Models\Category;
use Domain\Category\Resources\CategoryResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Support\Helper\Helper;

class ListCategoriesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
        bool $isPagination = true
    ): LengthAwarePaginator|Collection
    {
        $search = $params['search'] ?? null;
        $page = $params['page'] ?? null;
        $type = $params['type'] ?? null;
        $parentId = $params['parentId'] ?? null;

        return Category::query()
            ->latest('created_at')
            ->when($this->requestTypeCheck(), function (Builder $builder) {
                return $builder->where('is_active', true);
            })
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where('title', 'LIKE', "%$search%")
                    ->orWhere('slug', 'LIKE', "%$search%");
            })
            ->when($type, function (Builder $builder) use ($type, $parentId) {
                return $builder->when($type === 'parent', function (Builder $builder) {
                    return $builder->whereNull('parent_id');
                })->when($type === 'child' && isset($parentId), function (Builder $builder) use ($parentId) {
                    return $builder->whereNotNull('parent_id')->where('parent_id', $parentId);
                });
            })
            ->orderBy('title')
            ->when($isPagination, function (Builder $builder) use ($params, $page) {
                $limit = $params['limit'] ?? 20;
                return $builder->paginate($limit, ['*'], 'page', $page)
                    ->withQueryString()
                    ->through(function ($category) {
                        return CategoryResources::make($category);
                    });
            }, function (Builder $builder) {
                return $builder->get()->map(function ($category) {
                    return [
                        'value' => $category->id,
                        'label' => $category->title,
                    ];
                });
            });

    }
}