<?php

namespace Domain\Banner\ViewModels;

use Domain\Banner\Models\Banner;
use Spatie\ViewModels\ViewModel;
use Domain\Banner\Resources\BannerResource;
use Domain\Banner\Actions\ListBannersAction;
use Illuminate\Pagination\LengthAwarePaginator;

class BannerViewModel extends ViewModel
{
    public function __construct(
        public int $perPage,
        public ListBannersAction $listBannersAction,
        public ?Banner $banner = null
    ) {
    }

    public function banners(): LengthAwarePaginator
    {
        $params = [
            'limit' => request()->has('limit') ? request()->get('limit') : 10,
            'search' => request()->has('search') ? request()->get('search') : null,
        ];

        return $this->listBannersAction->execute($params);
    }

    public function banner(): array|BannerResource
    {
        if ($this->banner) {
            return BannerResource::make(
                $this->banner
            );
        }

        return [];
    }
}
