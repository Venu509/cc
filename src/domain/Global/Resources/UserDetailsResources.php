<?php

namespace Domain\Global\Resources;

use Domain\Global\Helpers\EmployeeHelper;
use Domain\User\Enums\ProfileCompletion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonException;

class UserDetailsResources extends JsonResource
{
    /**
     * @throws JsonException
     */
    public function toArray(Request $request): array
    {
        $role = $this->roles()->first()->name;

        $employeeHelper = new EmployeeHelper();
        $mainData = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'via' => $this->login_via,
            'alternativeNumber' => $this->alternative_number,
            'phone' => $this->phone,
            'avatar' => $employeeHelper->avatar($this),
            'userDetail' => $this->userDetail($this, $role),
        ];

        if($role === 'candidate') {
            return array_merge($mainData, [
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

                'profileCompletion' => auth()->check() ? auth()->user()->getCompletionPercentage() : null,
                'isProfileCompleted' => auth()->check() ? auth()->user()->isStepCompleted(ProfileCompletion::STEP_FIVE) : null,
            ]);
        }

        return $mainData;
    }

    /**
     * @throws JsonException
     */
    private function userDetail($user, string $role): array
    {
        $employeeHelper = new EmployeeHelper();

        $mainData = [
            'companyName' => $user->userDetail->company_name,
            'fullName' => $user->userDetail->full_name,
            'gender' => $user->userDetail->gender,
            'age' => $user->userDetail->age,
            'dob' => $user->userDetail->dob,
            'resume' => $user->userDetail->resume ? imageCheck('resumes/thumbnail/' . $user->userDetail->resume) : null,
            'experience' => $user->userDetail->experience,

            'companyDetails' => [
                'companyMobileNumber' => $user->userDetail->company_mobile_number,
                'companyEmail' => $user->userDetail->company_email,
                'contactPerson' => $user->userDetail->contact_person,
                'contactPersonEmail' => $user->userDetail->contact_person_email,
                'contactPersonPhone' => $user->userDetail->contact_person_phone,
                'yearsOfExistence' => $user->userDetail->years_of_existence,
                'contactPersonAddress' => $user->userDetail->contact_person_address,
                'dateOfRegister' => $user->userDetail->date_of_register,
                'registrationDoc' => $user->userDetail->registration_doc ? imageCheck('user-details/registration-documents/thumbnail/' . $user->userDetail->registration_doc) : null,
            ],
        ];

        if($role === 'candidate') {
            return array_merge($mainData, [
                'address' => [
                    'street' => $user->userDetail->street,
                    'city' => $user->userDetail->city,
                    'state' => $user->userDetail->state,
                    'postalCode' => $user->userDetail->postal_code,
                    'country' => $user->userDetail->country,
                ],
                'professionalInformation' => [
                    'currentJobTitle' => $user->userDetail->current_job_title,
                    'currentCompany' => $user->userDetail->current_company,
                    'currentSalary' => $user->userDetail->current_salary,
                    'expectedSalary' => $user->userDetail->expected_salary,
                    'noticePeriod' => $user->userDetail->notice_period === 'more' ? '90+ days' : getStringFromSlug($user->userDetail->notice_period),
                    'canRelocated' => $user->userDetail->can_relocated,
                ],

                'jobPreferences' => [
                    'preferredJobType' => $employeeHelper->jobPreferences($user)['Type'] ?? null,
                    'preferredJobIndustry' => $employeeHelper->jobPreferences($user)['Industry'] ?? null,
                    'preferredJobRole' => $employeeHelper->jobPreferences($user)['Role'] ?? null,
                    'preferredJobLocation' => $employeeHelper->jobPreferences($user)['Location'] ?? null,
                    'preferredJobEmploymentStatus' => $employeeHelper->jobPreferences($user)['EmploymentStatus'] ?? null,
                ],

                'academic' => [
                    'specializedIn' => $user->userDetail->specialized_in,
                    'university' => $user->userDetail->university,
                    'yearOfGraduation' => $user->userDetail->year_of_graduation,
                    'additionalQualification' => $user->userDetail->additional_qualification,
                    'qualification' => getStringFromSlug($user->userDetail->qualification),
                ]
            ]);
        }

        if($role === 'company') {
            return array_merge([
                'address' => $user->userDetail->address,
                'gst' => $user->userDetail->gst,
            ], $mainData);
        }

        return $mainData;
    }
}