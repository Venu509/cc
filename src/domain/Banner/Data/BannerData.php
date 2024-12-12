<?php

namespace Domain\Banner\Data;

use Illuminate\Http\UploadedFile;

class BannerData
{
    public function __construct(
        public string $title,
        public ?UploadedFile $image,
        public ?string $url
    ) {
    }
}
