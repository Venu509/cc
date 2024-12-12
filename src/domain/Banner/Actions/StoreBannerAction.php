<?php

namespace Domain\Banner\Actions;

use Support\Helper\Helper;
use Domain\Banner\Models\Banner;
use Domain\Banner\Data\BannerData;

class StoreBannerAction
{
    use Helper;

    public function execute(
        BannerData $bannerData,
        Banner $banner = new Banner()
    ): void {
        $banner->forceFill([
            'title' => $bannerData->title,
            'url' => $bannerData->url,
            'image' => $this->saveFile(
                $banner,
                $bannerData->image,
                'image',
                'banners/'
            ),
            'is_active' => true,
            'modified_by' => auth()->user()->id,
            'added_by' => auth()->user()->id,
        ]);

        $banner->save();

        $banner->refresh();
    }
}
