<?php

namespace Domain\Student\Data;

use Illuminate\Http\UploadedFile;

class StudentData
{
    public function __construct(
        public ?string $id,
        public string $firstName,
        public string $lastName,
        public string $dateOfBirth,
        public string $mobileNumber,
        public string $email,
        public string $address,
        public ?UploadedFile  $resume,
        public ?string  $panNumber,
        public string  $aadhaarNumber,
        public string  $qualification,
        public string  $gender,
        public string  $maritalStatus,
        public ?UploadedFile  $profilePicture,
        public mixed  $branch,
        public string  $studentId,
    ) {
    }
}
