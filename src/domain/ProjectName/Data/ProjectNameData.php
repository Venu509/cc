<?php

namespace Domain\ProjectName\Data;

use Illuminate\Http\UploadedFile;

class ProjectNameData
{
    public function __construct(
        public ?string $id,
        public string $name,
    ) {
    }
}
