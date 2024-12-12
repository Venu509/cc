<?php

namespace Domain\Global\Actions;

use App\Models\User;
use Domain\API\Authentication\Mail\UserRegister;
use Domain\Candidate\Models\SkillUser;
use Domain\Candidate\Models\WorkExperience;
use Domain\Global\Data\EmployeeData;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Domain\Skill\Models\Skill;
use Domain\User\Enums\ProfileCompletion;
use Domain\User\Models\UserDetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use JsonException;
use Spatie\Permission\Models\Role;
use Support\Helper\Helper;

class StoreEmployeeAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        EmployeeData $employeeData,
        User $user = null,
        ?string $method = 'store'
    ): User {

        $role = $employeeData->type['value'];
        $password = Str::password(8);

        $user = $user ?: new User();

        if($employeeData->tab === 'personal-details') {
            $user = $this->fillUserDetails($user, $employeeData, $method, $password, $role);
        }

        $user->save();

        $user->markStepAsCompleted(ProfileCompletion::STEP_ONE);

        $user->refresh();

        $this->syncRolesAndPermissions($user, $role);

        $userDetails = $this->getOrCreateUserDetails($user);

        if (in_array($role, ['government', 'institution'], true)) {
            $userDetails = $this->updateCollegeDetails($userDetails, $employeeData);

            $user->markStepAsCompleted(ProfileCompletion::STEP_EIGHT);
        } elseif ($role === 'candidate') {
            $userDetails = $this->updateCandidateDetails($userDetails, $employeeData, $user);
        } elseif ($role === 'company') {
            $userDetails = $this->updateCompanyDetails($userDetails, $employeeData, $user);

            $user->markStepAsCompleted(ProfileCompletion::STEP_EIGHT);
        }

        $userDetails->forceFill([
            'user_id' => $user->id,
            'registered_at' => now(),
        ]);

        $userDetails->save();

        $userDetails->refresh();

        $this->sendNotificationsAndEmails($user, $employeeData, $password ?? null, $method, $role);

        return $user;
    }

    protected function updateCompanyDetails(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'company_name' => $employeeData->companyName,
            'company_mobile_number' => null,
            'company_email' => null,
            'contact_person' => $employeeData->contactPerson,
            'contact_person_email' => $employeeData->contactPersonEmail,
            'contact_person_phone' => $employeeData->contactPersonPhone,
            'contact_person_address' => $employeeData->contactPersonAddress,
            'gst' => $employeeData->gst,
            'address' => $employeeData->address,
            'registration_doc' => $this->saveFile(
                $userDetails,
                $employeeData->registrationDoc,
                'registration_doc',
                'user-details/registration-documents/'
            ),
        ]);

        return $userDetails;
    }

    protected function fillUserDetails(User $user, EmployeeData $employeeData, ?string $method, string $password, string $role): User
    {
        $user->forceFill([
            'name' => $role === 'candidate' ?  ($method === 'update' ? $user->name : $employeeData->fullName) : ($role === 'company' ? $employeeData->companyName : $employeeData->name),
            'email' => $role === 'company' ? $employeeData->contactPersonEmail : $employeeData->email,
            'phone' => $employeeData->mobileNumber,
            'username' => $employeeData->username,
            'avatar' => $this->saveFile(
                $user,
                $employeeData->avatar,
                'avatar',
                'user-details/avatars/',
            ),
            'alternative_number' => $employeeData->alternativeNumber,
            'login_via' => $method === 'update' ? $user->login_via : $employeeData->via,
        ]);

        if ($method === 'store') {
            $user->password = Hash::make($password);
            $user->modified_by = $user->added_by = auth()->user()->id;
        } elseif ($method === 'update') {
            $user->modified_by = auth()->user()->id;
        }

        return $user;
    }

    protected function syncRolesAndPermissions(User $user, string $role): void
    {
        $user->syncRoles($role);

        $permissions = Role::query()
            ->where('name', $role)
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->toArray();

        $user->syncPermissions($permissions);
    }

    protected function getOrCreateUserDetails(User $user): UserDetails
    {
        return UserDetails::firstOrNew(['user_id' => $user->id]);
    }

    /**
     * @throws JsonException
     */
    protected function updateCandidateDetails(UserDetails $userDetails, EmployeeData $employeeData, User $user): UserDetails
    {
        if ($employeeData->tab === 'personal-details') {
            $userDetails = $this->updateCandidatePersonalDetails($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_ONE);
        } elseif ($employeeData->tab === 'professional-information') {
            $userDetails = $this->updateCandidateProfessionalInformation($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_TWO);
        } elseif ($employeeData->tab === 'educational-background') {
            $userDetails = $this->updateCandidateEducationalBackground($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_THREE);
        } elseif ($employeeData->tab === 'work-experiences') {
            WorkExperience::query()->where('candidate_id', $user->id)->delete();
            collect($employeeData->candidateExperiences)->each(function ($experience) use ($user) {
                $workExperience = new WorkExperience();

                $workExperience = $this->updateCandidateWorkExperience($workExperience, $experience, $user);

                $workExperience->save();
                $workExperience->refresh();
            });
            $user->markStepAsCompleted(ProfileCompletion::STEP_FOUR);
        } elseif ($employeeData->tab === 'skill-and-certificates') {
            (new EmployeeHelper())->storeKeySkills($employeeData, $user);

            $userDetails = $this->updateCertificationAndKnownLanguages($userDetails, $employeeData, $user);
            $user->markStepAsCompleted(ProfileCompletion::STEP_FIVE);
        } elseif ($employeeData->tab === 'resume-and-portfolio') {
            $userDetails = $this->updateResumeAndPortfolio($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_SIX);
        } elseif ($employeeData->tab === 'job-preferences') {
            $userDetails = $this->updateCandidateJobPreferences($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_SEVEN);
        } elseif ($employeeData->tab === 'additional-information') {
            $userDetails = $this->updateCandidateAdditionalInformation($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_EIGHT);
        }

        return $userDetails;
    }

    protected function updateCollegeDetails(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'company_name' => $employeeData->name,
            'company_mobile_number' => $employeeData->mobileNumber,
            'company_email' => $employeeData->email,
            'contact_person' => $employeeData->contactPerson,
            'contact_person_email' => $employeeData->contactPersonEmail,
            'contact_person_phone' => $employeeData->contactPersonPhone,
            'contact_person_address' => $employeeData->contactPersonAddress,
            'address' => $employeeData->address,
            'years_of_existence' => $employeeData->yearsOfExistence,
            'registration_doc' => $this->saveFile(
                $userDetails,
                $employeeData->registrationDoc,
                'registration_doc',
                'user-details/registration-documents/'
            ),
            'date_of_register' => $employeeData->dateOfRegister,
        ]);

        return $userDetails;
    }

    protected function updateCandidatePersonalDetails(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $country = $employeeData->country['label'] ?? $employeeData->country['value'];

        $userDetails->forceFill([
            'full_name' => $employeeData->fullName,
            'dob' => $employeeData->dob,
            'age' => calculateAge($employeeData->dob),
            'gender' => $employeeData->gender,
            'marital_status' => $employeeData->maritalStatus,
            'street' => $employeeData->street,
            'city' => $employeeData->city,
            'state' => $employeeData->state,
            'postal_code' => $employeeData->postalCode,
            'country' => $country,
            'gst' => $employeeData->gst,
            'address' => $employeeData->address ?: ($employeeData->street . ' ' . $employeeData->city . ' ' . $employeeData->state . ' ' . $employeeData->postalCode . ' ' . $country),
            'resume' => $this->saveFile(
                $userDetails,
                $employeeData->resume,
                'resume',
                'resumes/'
            ),
        ]);

        return $userDetails;
    }

    protected function updateCandidateProfessionalInformation(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'current_job_title' => $employeeData->currentJobTitle,
            'current_company' => $employeeData->currentCompany,
            'no_of_experiences' => $employeeData->noOfExperience['value'],
            'current_salary' => $employeeData->currentSalary,
            'expected_salary' => $employeeData->expectedSalary,
            'notice_period' => $employeeData->noticePeriod['value'],
            'can_relocated' => $employeeData->canRelocated,
        ]);

        return $userDetails;
    }

    protected function updateCandidateEducationalBackground(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'qualification' => $employeeData->qualification['value'],
            'specialized_in' => $employeeData->specializedIn,
            'university' => $employeeData->university,
            'year_of_graduation' => $employeeData->yearOfGraduation,
            'additional_qualification' => $employeeData->additionalQualification,
        ]);

        return $userDetails;
    }

    protected function updateCandidateWorkExperience(WorkExperience $workExperience, $experience, User $user): WorkExperience
    {
        $workExperience->forceFill([
            'company' => $experience['companyName'],
            'job_title' => $experience['jobTitle'],
            'start_date' => $experience['startDate'],
            'end_date' => $experience['endDate'],
            'is_still_working' => $experience['isCurrentEmployer'] === 'yes',
            'responsibilities' => $experience['responsibilities'],
            'achievements' => $experience['achievements'],
            'candidate_id' => $user->id,
        ]);

        return $workExperience;
    }

    protected function updateCandidateKeySkills(SkillUser $skillUser, Skill|Builder $skill, User $user): SkillUser
    {
        $skillUser->forceFill([
            'skill_id' => $skill->id,
            'user_id' => $user->id,
        ]);

        return $skillUser;
    }

    protected function createNewSkill(Skill $skill, array $data, User $user): Skill
    {
        $skill->forceFill([
            'title' => $data['label'],
            'slug' => slugGenerator($data['label']),
            'modified_by' => $user->id,
            'added_by' => $user->id,
        ]);

        return $skill;
    }

    protected function updateCertificationAndKnownLanguages(UserDetails $userDetails, EmployeeData $employeeData, User $user): UserDetails
    {
        $userDetails->forceFill([
            'certifications' => $employeeData->certifications,
            'known_languages' => $employeeData->knownLanguages,
        ]);

        return $userDetails;
    }

    protected function updateResumeAndPortfolio(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'portfolio' => $this->saveFile(
                $userDetails,
                $employeeData->portfolio,
                'portfolio',
                'user-details/portfolios/'
            ),
            'cover_letter_type' => $employeeData->coverLetterType,
            'cover_letter' => $employeeData->coverLetter,
            'cover_letter_file' => $this->saveFile(
                $userDetails,
                $employeeData->coverLetterFile,
                'cover_letter_file',
                'user-details/cover-letters/'
            ),
        ]);

        return $userDetails;
    }

    /**
     * @throws JsonException
     */
    protected function updateCandidateJobPreferences(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'job_preferences' => json_encode([
                'Type' =>  [
                    'value' => $employeeData->preferredJobType['value'],
                    'label' => $employeeData->preferredJobType['label'] ?? ucfirst($employeeData->preferredJobType['value']),
                ],
                'Industry' => [
                    'value' => $employeeData->preferredJobIndustry['value'],
                    'label' => $employeeData->preferredJobIndustry['label'] ?? ucfirst($employeeData->preferredJobIndustry['value']),
                ],
                'Role' => $employeeData->preferredJobRole,
                'Location' => collect($employeeData->preferredJobLocation)->map(function ($location) {
                    return [
                        'value' => slugGenerator($location['value']),
                        'label' => ucfirst($location['value']),
                    ];
                }),
                'EmploymentStatus' => [
                    'value' => $employeeData->preferredJobEmploymentStatus['value'],
                    'label' => $employeeData->preferredJobEmploymentStatus['label'] ?? ucfirst($employeeData->preferredJobEmploymentStatus['value']),
                ],
            ], JSON_THROW_ON_ERROR | true),
        ]);

        return $userDetails;
    }

    protected function updateCandidateAdditionalInformation(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'career_objective' => $employeeData->careerObjective,
            'awards_and_recognitions' => $employeeData->awardsAndRecognitions,
        ]);

        return $userDetails;
    }

    /**
     * @throws JsonException
     */
    protected function sendNotificationsAndEmails(User $user, EmployeeData $employeeData, ?string $password, string $method, string $role): void
    {
        if ($method === 'store') {
            $notificationAction = new StoreNotificationAction();
            $notificationData = new NotificationData(
                $user->id,
                $employeeData->type['value'],
                domainStates('register'),
                'Welcome to Dream Career',
                'You\'ve successfully registered to Dream Career'
            );

            $notificationAction->execute($notificationData, user: $user);

            if ($employeeData->via === 'email') {
                $email = $employeeData->username;

                if (app()->environment('production') || app()->environment('staging')) {
                    Mail::to($email)->send(new UserRegister($employeeData, $password));
                }
            }
        }
    }
}

