<?php

namespace Domain\MyAccount\ViewModels;

use App\Models\User;
use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class MyAccountViewModel extends ViewModel
{
    public function __construct(
        public ?User $oldUser = null
    )
    {
    }

    public function candidate(): array
    {
        $userDetails = $this->getUserDetails($this->oldUser);

        return [
            'id' => $this->oldUser?->id,
            'tab' => 'personal-details',
            'email' => $this->oldUser?->email,
            'via' => $this->oldUser->login_via ?? 'email',
            'mobileNumber' => $this->oldUser?->phone,
            'name' => $this->oldUser?->name,
            'username' => $this->oldUser?->username,
            'address' => $userDetails?->address,
            'alternativeNumber' => $this->oldUser?->alternative_number,

            'avatar' => $this->oldUser?->avatar ? imageCheck('user-details/avatars/thumbnail/' . $this->oldUser?->avatar) : null,
            'profilePreview' => $this->oldUser?->avatar ? imageCheck('user-details/avatars/thumbnail/' . $this->oldUser?->avatar) : null,
            'registrationDoc' => $this->oldUser?->registrationDoc ? imageCheck('user-details/registration-documents/thumbnail/' . $this->oldUser?->registrationDoc) : null,
            'registrationDocPreview' => $userDetails?->registration_doc ? imageCheck('user-details/registration-documents/thumbnail/' . $userDetails?->registration_doc) : null,

            'fullName' => $userDetails?->full_name,
            'dob' => $userDetails?->dob,
            'gender' => $userDetails?->gender,
            'street' => $userDetails?->street,
            'maritalStatus' => $userDetails?->marital_status,
            'city' => $userDetails?->city,
            'state' => $userDetails?->state,
            'postalCode' => $userDetails?->postal_code,
            'country' => [
                'value' => $userDetails?->country,
                'label' => getStringFromSlug($userDetails?->country),
            ],
            'type' => [
                'value' => $this->oldUser ? $this->oldUser->roles()->first()->name : 'candidate',
                'label' => $this->oldUser ? $this->oldUser->roles()->first()->display_name : 'Candidate',
            ],
            'getCompletionStatus' => $this->oldUser?->getCompletionStatus(),

            'companyName' => $userDetails?->company_name,
            'contactPerson' => $userDetails?->contact_person,
            'contactPersonEmail' => $userDetails?->contact_person_email,
            'contactPersonPhone' => $userDetails?->contact_person_phone,
            'contactPersonAddress' => $userDetails?->contact_person_address,
            'yearsOfExistence' => $userDetails?->years_of_existence,
            'dateOfRegister' => $userDetails?->date_of_register,
            'gst' => $userDetails?->gst,

            'currentJobTitle' => $userDetails?->current_job_title,
            'currentCompany' => $userDetails?->current_company,
            'currentSalary' => $userDetails?->current_salary,
            'expectedSalary' => $userDetails?->expected_salary,
            'canRelocated' => $userDetails?->can_relocated ? 'yes' : 'no',
            'noOfExperience' => [
                'value' => $userDetails?->no_of_experiences,
                'label' => getStringFromSlug($userDetails?->no_of_experiences),
            ],
            'noticePeriod' => [
                'value' => $userDetails?->notice_period,
                'label' => getStringFromSlug($userDetails?->notice_period),
            ],

            'qualification' => [
                'value' => $userDetails?->qualification,
                'label' => getStringFromSlug($userDetails?->qualification),
            ],
            'specializedIn' => $userDetails?->specialized_in,
            'university' => $userDetails?->university,
            'yearOfGraduation' => $userDetails?->year_of_graduation,
            'additionalQualification' => $userDetails?->additional_qualification,
            'hasAdditionalQualification' => $userDetails?->additional_qualification ? 'yes' : 'no',

            'candidateExperiences' => $this->oldUser ? collect($this->oldUser->workExperiences)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'companyName' => $item->company,
                    'jobTitle' => $item->job_title,
                    'startDate' => $item->start_date,
                    'endDate' => $item->end_date,
                    'responsibilities' => $item->responsibilities,
                    'achievements' => $item->achievements,
                    'otherExperiences' => $item->other_experiences,
                ];
            })->toArray() : [],

            'keySkills' => $this->oldUser ? collect($this->oldUser->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,

                    'label' => $item->title,
                    'value' => $item->slug,
                ];
            })->toArray() : [],
            'certifications' => $userDetails?->certifications,
            'knownLanguages' => $userDetails?->known_languages,

            'resume' => $userDetails?->resume ? imageCheck('resumes/thumbnail/' . $userDetails?->resume) : null,
            'portfolio' => imageCheck('user-details/portfolios/thumbnail/' . $userDetails?->portfolio),
            'coverLetter' => $userDetails?->cover_letter,
            'coverLetterType' => $userDetails?->cover_letter_type ?? 'text',
            'coverLetterFile' => imageCheck('user-details/cover-letters/thumbnail/' . $userDetails?->cover_letter_file),

            'preferredJobType' => $this->jobPreferences($this->oldUser)['Type'] ?? null,
            'preferredJobIndustry' => $this->jobPreferences($this->oldUser)['Industry'] ?? null,
            'preferredJobRole' => $this->jobPreferences($this->oldUser)['Role'] ?? null,
            'preferredJobLocation' => $this->jobPreferences($this->oldUser)['Location'] ?? null,
            'preferredJobEmploymentStatus' => $this->jobPreferences($this->oldUser)['EmploymentStatus'] ?? null,

            'carerObjective' => $userDetails?->carer_objective,
            'awardsAndRecognitions' => $userDetails?->awards_and_recognitions,
        ];
    }

    public function countries(): Collection
    {
        return (new EmployeeHelper())->countries();
    }

    public function noticePeriods(): Collection
    {
        return (new EmployeeHelper())->noticePeriods();
    }

    public function noOfExperiences(): Collection
    {
        return (new EmployeeHelper())->noOfExperiences();
    }

    public function qualifications(): Collection
    {
        return (new EmployeeHelper())->qualifications();
    }

    public function keySkills(): Collection
    {
        return (new EmployeeHelper())->keySkills();
    }

    public function jobTypes(): Collection
    {
        return (new EmployeeHelper())->workModes();
    }

    public function industries(): Collection
    {
        return (new EmployeeHelper())->industries();
    }

    public function employmentStatus(): Collection
    {
        return (new EmployeeHelper())->employmentStatus();
    }

    private function getUserDetails($user)
    {
        return $user ? $user->userDetail : null;
    }

    /**
     * @throws JsonException
     */
    protected function jobPreferences($user): ?array
    {
        $jobPreferences = $this->getUserDetails($user)?->job_preferences;

        if($jobPreferences) {
            return json_decode($jobPreferences, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }
}
