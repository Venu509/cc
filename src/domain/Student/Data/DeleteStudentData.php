<?php

namespace Domain\Student\Data;

use Illuminate\Http\UploadedFile;

class DeleteStudentData
{
    public function __construct(
        public ?array $ids,
        public string $role,
        public int $user,
        public string $type,
    ) {
    }
}
