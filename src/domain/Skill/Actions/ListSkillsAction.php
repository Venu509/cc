<?php

namespace Domain\Skill\Actions;

use Domain\Skill\Models\Skill;
use Domain\Skill\Resources\SkillResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Support\Helper\Helper;

class ListSkillsAction
{
    use Helper;

    public function execute(
        ?array $params = [],
        ?bool $isPaginatedData = true,
    ): LengthAwarePaginator|Collection {
        $search = $params['search'] ?? null;
        $page = $params['page'] ?? null;
        $limit = $params['limit'] ?? 10;

        return Skill::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function(Builder $builder) use ($search) {
                    return $builder
                        ->where('title', 'LIKE', "%$search%")
                        ->where('slug', 'LIKE', "%$search%");
                });
            })
            ->orderBy('title')
            ->when(!$isPaginatedData, function (Builder $builder) {
                return $builder
                    ->get()
                    ->map(function ($skill) {
                        return [
                            'value' => $skill->slug,
                            'label' => $skill->title,
                        ];
                    });
            })->when($isPaginatedData, function (Builder $builder) use ($limit, $page) {
                return $builder
                    ->paginate($limit, ['*'], 'page', $page)
                    ->withQueryString()
                    ->through(function ($skill) {
                        return SkillResources::make($skill);
                    });
            });
    }
}
