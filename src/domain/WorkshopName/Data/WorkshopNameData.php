<?php

namespace Domain\WorkshopName\Data;

use Illuminate\Http\UploadedFile;

class WorkshopNameData
{
    public function __construct(
        public ?string $id,
        public string $name,
    ) {
    }
}
