<?php

namespace Domain\Project\Data;

use Illuminate\Http\UploadedFile;

class ProjectData
{
    public function __construct(
        public ?string $id,
        public mixed $name,
        public string $type,
        public mixed  $branch,
        public string $semester,
        public ?string $description,
        public string $date,
        public string $endDate,
    ) {
    }
}
