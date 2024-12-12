<?php

namespace Domain\Workshop\Data;

use Illuminate\Http\UploadedFile;

class WorkshopData
{
    public function __construct(
        public ?string $id,
        public mixed $name,
        public mixed  $branch,
        public string $semester,
        public ?string $description,
        public string $date,
        public string $endDate,
    ) {
    }
}
