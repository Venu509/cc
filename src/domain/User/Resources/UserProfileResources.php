<?php

namespace Domain\User\Resources;

use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonException;
use Support\Helper\Helper;

class UserProfileResources extends JsonResource
{
    use Helper;

    public ?array $requiredSkills = [];
    public ?string $vacancyId = null;

    public function __construct($resource, ?array $requiredSkills = [], ?string $vacancyId = null)
    {
        $this->requiredSkills = $requiredSkills;
        $this->vacancyId = $vacancyId;

        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $employeeHelper = new EmployeeHelper();

        $userVacancy = $this->appliedJobs()
            ->where('vacancy_id', $this->vacancyId)
            ->first();

        $userSkills = array_merge(
            $this->skills->pluck('title')->toArray(),
            [getStringFromSlug($this->userDetail->qualification)],
            [$this->userDetail->experience],
            [$this->userDetail->skill_set],
            [$this->userDetail->additional_qualification],
        );

        return [
            'id' => $this->id,
            'role' => $this->roles[0]->display_name,
            'roleSlug' => $this->roles[0]->name,
            'roleColor' => $this->color($this->roles[0]->name),
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'alternativeNumber' => $this->alternative_number,
            'phone' => $this->phone,
            'via' => $this->login_via,
            'avatar' => $employeeHelper->avatar($this),
            'isActive' => $this->is_active,
            'lastAccessedBy' => $this->modifiedBy->name,
            'userDetail' => $this->userDetail($this, $employeeHelper),
            'workExperiences' => $employeeHelper->workExperiences($this)->get('workExperiences'),
            'totalWorkExperiences' => $employeeHelper->workExperiences($this)->get('totalExperience'),

            'keySkills' => $this->skills ? collect($this->skills)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'title' => $item->title,
                    'slug' => $item->slug,
                ];
            })->sortBy(function ($item) {
                return $item['title'];
            })->values()->toArray() : [],

            'appliedJobs' => $employeeHelper->appliedVacancies($this),
            'matchedPercentage' => $employeeHelper->calculateSkillMatchPercentage($userSkills, $this->requiredSkills),
            'vacancyStatus' => $userVacancy ? $userVacancy->status : 'applied',
            'applicantTracking' => $userVacancy ? $userVacancy->history : [],
            'vacancyStatusColor' => $userVacancy ? $this->applicantStatusColor($userVacancy->status) : 'pending',
        ];
    }

    /**
     * @throws JsonException
     */
    private function userDetail($user, EmployeeHelper $employeeHelper): array
    {
        return [
            'id' => $user->userDetail->id,
            'fullName' => $user->userDetail->full_name ?? $user->userDetail->full_name,
            'studentID' => $user->userDetail->student_id,
            'gender' => $user->userDetail->gender,
            'address' => $user->userDetail->address,
            'street' => $user->userDetail->street,
            'city' => $user->userDetail->city,
            'state' => $user->userDetail->state,
            'postalCode' => $user->userDetail->postal_code,
            'country' => $user->userDetail->country,
            'age' => $user->userDetail->age,
            'dob' => $user->userDetail->dob,
            'branch' =>  [
                'id' => $user->userDetail->branch ? $user->userDetail->branch->id : 'Not Assigned',
                'name' => $user->userDetail->branch ? $user->userDetail->branch->name : 'Not Assigned',
                'slug' => $user->userDetail->branch ? $user->userDetail->branch->slug : 'Not Assigned',
            ],
            'panCardNumber' => $user->userDetail->pan_card_number,
            'adharCardNumber' => $user->userDetail->adhar_card_number,
            'noOfExperiences' => $user->userDetail->no_of_experiences,
            'yearsOfExistence' => $user->userDetail->years_of_existence,
            'qualification' => getStringFromSlug($user->userDetail->qualification),
            'maritalStatus' => $user->userDetail->marital_status,
            'resume' => $user->userDetail->resume ? imageCheck('resumes/thumbnail/' . $user->userDetail->resume) : null,
            'experience' => $user->userDetail->experience,
            'skillSet' => $user->userDetail->skill_set,
            'companyName' => $user->userDetail->company_name,
            'companyMobileNumber' => $user->userDetail->company_mobile_number,
            'companyEmail' => $user->userDetail->company_email,
            'contactPerson' => $user->userDetail->contact_person,
            'contactPersonEmail' => $user->userDetail->contact_person_email,
            'contactPersonPhone' => $user->userDetail->contact_person_phone,
            'contactPersonAddress' => $user->userDetail->contact_person_address,
            'dateOfRegister' => $user->userDetail->date_of_register,
            'companyUrl' => $user->userDetail->company_url,
            'companyLogo' => $user->userDetail->company_logo ? imageCheck('user-details/thumbnail/' . $user->userDetail->company_logo) : null,
            'registrationDoc' => $user->userDetail->registration_doc ? imageCheck('user-details/registration-documents/thumbnail/' . $user->userDetail->registration_doc) : null,


            'currentJobTitle' => $user->userDetail->current_job_title,
            'currentCompany' => $user->userDetail->current_company,
            'currentSalary' => $user->userDetail->current_salary,
            'expectedSalary' => $user->userDetail->expected_salary,
            'noticePeriod' => $user->userDetail->notice_period === 'more' ? '90+ days' : getStringFromSlug($user->userDetail->notice_period),
            'canRelocated' => $user->userDetail->can_relocated,

            'preferredJobType' => $employeeHelper->jobPreferences($user)['Type'] ?? null,
            'preferredJobIndustry' => $employeeHelper->jobPreferences($user)['Industry'] ?? null,
            'preferredJobRole' => $employeeHelper->jobPreferences($user)['Role'] ?? null,
            'preferredJobLocation' => $employeeHelper->jobPreferences($user)['Location'] ?? null,
            'preferredJobEmploymentStatus' => $employeeHelper->jobPreferences($user)['EmploymentStatus'] ?? null,

            'specializedIn' => $user->userDetail->specialized_in,
            'university' => $user->userDetail->university,
            'yearOfGraduation' => $user->userDetail->year_of_graduation,
            'additionalQualification' => $user->userDetail->additional_qualification,

            'user_id' => $user->userDetail->user_id,
        ];
    }
}
