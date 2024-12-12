<?php

namespace Domain\API\Authentication\Data;

use Illuminate\Http\UploadedFile;

class RegisterData
{
    public function __construct(
        public mixed $userId,
        public string $selectedRole,
        public string $via,
        public ?string $phone,
        public ?string $email,
        public ?string $name,
        public ?string $companyName,
        public ?string $contactPerson,
        public ?string $contactPersonEmail,
        public ?string $contactPersonPhone,
        public ?string $contactPersonAddress,
        public ?string $dateOfRegistration,
        public ?string $username,
        public ?string $websiteUrl,
        public ?string $address,
        public ?string $password,
        public ?int $yearsOfExistence,
        public ?string $gst,
        public ?UploadedFile $avatar,
        public ?UploadedFile $registrationDoc,

        public ?string $fullName,
        public ?string $alternativeNumber,
        public ?string $preferredJobRole,
        public ?array $preferredJobLocation,
        public ?array $keySkills,
        public ?array $noticePeriod,
        public ?string $expectedSalary,
        public ?string $street,
        public ?string $city,
        public ?string $state,
        public ?string $postalCode,
        public ?array  $country,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $studentID,
        public ?string $gender,
        public ?string $dob,
        public ?string $panNumber,
        public ?string $aadharNumber,
        public ?string $qualification,
        public ?string $maritalStatus,
        public ?string $experience,
        public ?string $skillSet,
        public ?UploadedFile $resume,
    ) {
    }
}
