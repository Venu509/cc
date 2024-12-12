<?php

namespace Domain\User\Data;

use Illuminate\Http\UploadedFile;

class ProfileAvatarUploadData
{
    public function __construct(
        public string $role,
        public string $user,
        public ?UploadedFile $image,
    ) {
    }
}
