<?php

namespace Domain\Global\Data;

use Illuminate\Http\UploadedFile;

class EmployeeData
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $username,
        public ?string $address,
        public ?string $mobileNumber,
        public ?string $email,
        public ?string $yearsOfExistence,
        public ?string $dateOfRegister,
        public ?string $contactPerson,
        public ?string  $contactPersonEmail,
        public ?string  $contactPersonPhone,
        public ?string  $contactPersonAddress,
        public ?string  $gst,
        public array  $type,
        public ?UploadedFile  $registrationDoc,
        public ?UploadedFile  $avatar,

        public ?string  $fullName,
        public ?string  $dob,
        public ?string  $gender,
        public ?string  $maritalStatus,
        public ?string  $alternativeNumber,
        public ?string  $street,
        public ?string  $city,
        public ?string  $state,
        public ?string  $postalCode,
        public ?array  $country,

        public ?string  $currentJobTitle,
        public ?string  $currentCompany,
        public ?array  $noOfExperience,
        public ?string  $currentSalary,
        public ?string  $expectedSalary,
        public ?array  $noticePeriod,
        public ?bool  $canRelocated,

        public ?array  $qualification,
        public ?string  $specializedIn,
        public ?string  $university,
        public ?string  $yearOfGraduation,
        public ?string  $additionalQualification,

        public ?array  $candidateExperiences,

        public ?array  $keySkills,
        public ?string  $certifications,
        public ?string  $knownLanguages,

        public ?UploadedFile  $resume,
        public ?UploadedFile  $portfolio,
        public ?string  $coverLetter,
        public ?string  $coverLetterType,
        public ?UploadedFile  $coverLetterFile,

        public ?array  $preferredJobType,
        public ?array  $preferredJobIndustry,
        public ?array  $preferredJobEmploymentStatus,
        public ?string  $preferredJobRole,
        public ?array  $preferredJobLocation,

        public ?string  $careerObjective,
        public ?string  $awardsAndRecognitions,

        public ?string  $companyName,

        public ?string  $tab = 'personal-details',
        public ?string  $via = 'email',
    ) {
    }
}
