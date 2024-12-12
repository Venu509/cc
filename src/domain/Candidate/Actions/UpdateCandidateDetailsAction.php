<?php

namespace Domain\Candidate\Actions;

use App\Models\User;
use Domain\Candidate\Models\WorkExperience;
use Domain\Global\Data\EmployeeData;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Domain\User\Enums\ProfileCompletion;
use Domain\User\Models\UserDetails;
use JsonException;
use Support\Helper\Helper;

class UpdateCandidateDetailsAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        EmployeeData $employeeData,
        User $user,
        ?string $method = 'store'
    ): User {
        $role = $employeeData->type['value'];

        if($employeeData->tab === 'personal-details' || ($role === 'government' || $role === 'institution')) {
            $user->forceFill([
                'name' => $role === 'candidate' ? $employeeData->fullName : ($employeeData->companyName ?? $employeeData->name),
                'email' => $employeeData->email,
                'phone' => $employeeData->mobileNumber,
                'alternative_number' => $employeeData->alternativeNumber,
                'login_via' => $employeeData->via,
            ]);

            $user->save();
        }

        $user->markStepAsCompleted(ProfileCompletion::STEP_ONE);

        $user->refresh();

        $userDetails = $this->getOrCreateUserDetails($user);

        if($role === 'candidate') {
            $userDetails = $this->updateCandidateDetails($userDetails, $employeeData, $user);
        }

        if($role === 'company') {
            $userDetails = $this->updateCompanyDetails($userDetails, $employeeData, $user);
        }

        if($role === 'government' || $role === 'institution') {
            $userDetails = $this->updateCollageDetails($userDetails, $employeeData);
        }

        $userDetails->forceFill([
            'user_id' => $user->id,
        ]);

        $userDetails->save();

        $userDetails->refresh();

        $this->sendNotificationsAndEmails($user, $employeeData, $method, $role);

        return $user;
    }

    protected function updateCollageDetails(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
        $userDetails->forceFill([
            'company_name' => $employeeData->name,
            'company_mobile_number' => $employeeData->mobileNumber,
            'company_email' => $employeeData->email,
            'contact_person' => $employeeData->contactPerson,
            'contact_person_email' => $employeeData->contactPersonEmail,
            'contact_person_phone' => $employeeData->contactPersonPhone,
            'contact_person_address' => $employeeData->contactPersonAddress,
            'years_of_existence' => $employeeData->yearsOfExistence,
            'date_of_register' => $employeeData->dateOfRegister,
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

    protected function updateCompanyDetails(UserDetails $userDetails, EmployeeData $employeeData, User $user): UserDetails
    {

        if ($employeeData->tab === 'personal-details') {
            $userDetails->forceFill([
                'company_name' => $employeeData->companyName,
                'company_mobile_number' => $employeeData->mobileNumber,
                'company_email' => $employeeData->email,
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
        }

        if ($employeeData->tab === 'avatar') {
            $user = $this->updateCandidateAvatar($user, $employeeData);

            $user->save();
            $user->refresh();
        }
        return $userDetails;
    }

    protected function updateCandidateDetails(UserDetails $userDetails, EmployeeData $employeeData, User $user): UserDetails
    {
        if ($employeeData->tab === 'personal-details') {
            $userDetails = $this->updateCandidatePersonalDetails($userDetails, $employeeData);
            $user->markStepAsCompleted(ProfileCompletion::STEP_ONE);
        } elseif ($employeeData->tab === 'avatar') {
            $user = $this->updateCandidateAvatar($user, $employeeData);

            $user->save();
            $user->refresh();
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
        }

        return $userDetails;
    }

    protected function updateCandidateAvatar(User $user, EmployeeData $employeeData): User
    {
        $user->forceFill([
            'avatar' => $this->saveFile(
                $user,
                $employeeData->avatar,
                'avatar',
                'user-details/avatars/',
            ),
        ]);

        return $user;
    }

    protected function updateCandidatePersonalDetails(UserDetails $userDetails, EmployeeData $employeeData): UserDetails
    {
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
            'country' => $employeeData->country['value'],
            'address' => $employeeData->street . ' ' . $employeeData->city . ' ' . $employeeData->state . ' ' . $employeeData->postalCode . ' ' . $employeeData->country['label'],
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
            'end_date' => $experience['isCurrentEmployer'] === 'yes' ? null :$experience['endDate'],
            'is_still_working' => $experience['isCurrentEmployer'] === 'yes',
            'responsibilities' => $experience['responsibilities'],
            'achievements' => $experience['achievements'],
            'candidate_id' => $user->id,
        ]);

        return $workExperience;
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
            'resume' => $this->saveFile(
                $userDetails,
                $employeeData->resume,
                'resume',
                'resumes/'
            ),
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
                'Type' => [
                    'value' => $employeeData->preferredJobType['value'],
                    'label' => ucfirst($employeeData->preferredJobType['value']),
                ],
                'Industry' => [
                    'value' => $employeeData->preferredJobIndustry['value'],
                    'label' => ucfirst($employeeData->preferredJobIndustry['value']),
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
                    'label' => ucfirst($employeeData->preferredJobEmploymentStatus['value']),
                ],
            ], JSON_THROW_ON_ERROR | true),
        ]);

        return $userDetails;
    }

    protected function getOrCreateUserDetails(User $user): UserDetails
    {
        return UserDetails::firstOrNew(['user_id' => $user->id]);
    }

    /**
     * @throws JsonException
     */
    protected function sendNotificationsAndEmails(User $user, EmployeeData $employeeData, string $method, string $role): void
    {
        $notificationAction = new StoreNotificationAction();
        $notificationData = new NotificationData(
            $user->id,
            $employeeData->type['value'],
            domainStates($method === 'store' ? 'register' : 'updated'),
            $method === 'store' ? 'Welcome to Dream Career' : 'Profile Updated',
            $method === 'store' ? 'You\'ve successfully registered to Dream Career' : 'You\'ve successfully Updated your profile'
        );

        $notificationAction->execute($notificationData, user: $user);
    }
}