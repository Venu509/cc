<?php

namespace Domain\Country\Actions;

use Domain\Country\Models\Country;
use Domain\Country\Resources\CountryResources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Support\Helper\Helper;

class ListCountriesAction
{
    use Helper;

    public function execute(
        ?array $params = [],
        ?bool $isPaginatedData = true,
    ): LengthAwarePaginator {
        $search = $params['search'] ?? null;
        $page = $params['page'] ?? null;
        $limit = $params['limit'] ?? 10;

        return Country::query()
            ->when($search, function (Builder $builder) use ($search) {
                return $builder->where(function($queryBuilder) use ($search) {
                    return $queryBuilder
                        ->where('code', 'LIKE', "%$search%")
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('region', 'LIKE', "%$search%")
                        ->orWhere('timezones', 'LIKE', "%$search%")
                        ->orWhere('iso_numeric', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                });
            })
            ->orderBy('name')
            ->when(!$isPaginatedData, function (Builder $builder) {
                return $builder
                    ->get()
                    ->map(function ($country) {
                        return [
                            'value' => $country->code,
                            'label' => $country->name,
                        ];
                    });
            })->when($isPaginatedData, function (Builder $builder) use ($limit, $page) {
                return $builder
                    ->paginate($limit, ['*'], 'page', $page)
                    ->withQueryString()
                    ->through(function ($country) {
                        return CountryResources::make($country);
                    });
            });
    }
}
