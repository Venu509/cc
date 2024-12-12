<?php

namespace Domain\Student\Data;

use Illuminate\Http\UploadedFile;

class StudentBulkUploadData
{
    public function __construct(
        public ?UploadedFile  $file,
        public ?string  $fileUploadType,
    ) {
    }
}
