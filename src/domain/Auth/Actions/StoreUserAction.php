<?php

namespace Domain\Auth\Actions;

use App\Models\User;
use Domain\API\Authentication\Data\RegisterData;
use Domain\Candidate\Models\SkillUser;
use Domain\Global\Data\EmployeeData;
use Domain\Global\Helpers\EmployeeHelper;
use Domain\Notification\Actions\StoreNotificationAction;
use Domain\Notification\Data\NotificationData;
use Domain\Skill\Models\Skill;
use Domain\User\Enums\ProfileCompletion;
use Domain\User\Models\UserDetails;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JsonException;
use Spatie\Permission\Models\Role;
use Support\Helper\Helper;

class StoreUserAction
{
    use Helper;

    /**
     * @throws JsonException
     */
    public function execute(
        RegisterData $registerData,
        User         $user = new User(),
        ?string      $method = 'store',
    ): User
    {
        $name = $registerData->name;
        $password = Str::password(8);

        if($registerData->selectedRole === 'government' || $registerData->selectedRole === 'institution') {
            $name = $registerData->name ?? $registerData->companyName;
        } elseif ($registerData->selectedRole === 'candidate') {
            $name = $registerData->fullName;
        } elseif ($registerData->selectedRole === 'company') {
            $name = $registerData->companyName;
        }

        $user->name = $name;
        $user->email = $registerData->email;
        $user->phone = $registerData->phone;
        $user->username = $registerData->username;
        $user->alternative_number = $registerData->alternativeNumber;
        $user->login_via = $registerData->via;
        $user->avatar = $this->saveFile(
            $user,
            $registerData->avatar,
            'avatar',
            'user-details/avatars/',
        );

        if ($method === 'store') {
            $user->password = $registerData->password ? Hash::make($registerData->password) : Hash::make($password);
            $user->modified_by = User::first()->id;
            $user->added_by = User::first()->id;
        }

        if ($method === 'update') {
            $user->modified_by = auth()->user()->id;
        }

        $user->save();

        $user->refresh();
        $user->markStepAsCompleted(ProfileCompletion::STEP_ONE);

        $user->syncRoles($registerData->selectedRole);

        $permissions = Role::query()->where('name', $registerData->selectedRole)->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->toArray();

        $user->syncPermissions($permissions);

        if ($method === 'store') {
            $userDetails = new UserDetails();
        } else {
            $userDetails = UserDetails::where('user_id', $user->id)->first();
        }

        if ($registerData->selectedRole === 'government' || $registerData->selectedRole === 'institution') {
            $userDetails->forceFill([
                'company_name' => $registerData->companyName,
                'company_mobile_number' => $registerData->phone,
                'company_email' => $registerData->email,
                'contact_person' => $registerData->contactPerson,
                'contact_person_email' => $registerData->contactPersonEmail,
                'contact_person_phone' => $registerData->contactPersonPhone,
                'contact_person_address' => $registerData->contactPersonAddress,
                'company_url' => $registerData->websiteUrl,
                'address' => $registerData->address,
                'years_of_existence' => $registerData->yearsOfExistence,
                'registration_doc' => $this->saveFile(
                    $userDetails,
                    $registerData->registrationDoc,
                    'registration_doc',
                    'user-details/registration-documents/',
                ),
            ]);
        }

        if($registerData->selectedRole === 'candidate') {
            $userDetails->forceFill([
                'full_name' => $name,
                'student_id' => $registerData->studentID,
                'marital_status' => $registerData->maritalStatus,
                'age' => calculateAge($registerData->dob),
                'dob' => $registerData->dob,
                'pan_card_number' => $registerData->panNumber,
                'adhar_card_number' => $registerData->aadharNumber,
                'qualification' => $registerData->qualification ?? 'high-school',
                'gender' => $registerData->gender,
                'street' => $registerData->street,
                'city' => $registerData->city,
                'state' => $registerData->state,
                'postal_code' => $registerData->postalCode,
                'country' => $registerData->country['label'],
                'address' => $registerData->street . ' ' . $registerData->city . ' ' . $registerData->state . ' ' . $registerData->postalCode . ' ' . $registerData->country['label'],
                'resume' => $this->saveFile(
                    $userDetails,
                    $registerData->resume,
                    'resume',
                    'resumes/',
                ),
                'skill_set' => $registerData->skillSet,
                'notice_period' => $registerData->noticePeriod['value'],
                'expected_salary' => $registerData->expectedSalary,
            ]);

            (new EmployeeHelper())->storeKeySkills($registerData, $user);

            $this->updateCandidateJobPreferences($userDetails, $registerData);
        }

        if($registerData->selectedRole === 'company') {
            $userDetails = $this->updateCompanyDetails($userDetails, $registerData);
        }

        $userDetails->forceFill([
            'user_id' => $user->id,
            'registered_at' => now(),
        ]);

        $userDetails->save();

        $userDetails->refresh();

        if ($method === 'store') {
            (new StoreNotificationAction())->execute(
                new NotificationData(
                    $user->id,
                    $registerData->selectedRole,
                    domainStates('register'),
                    'Welcome to Dream Career',
                    'You\'ve successfully registered to Dream Career'
                ),
                user: $user
            );
        } else {
            (new StoreNotificationAction())->execute(
                new NotificationData(
                    $user->id,
                    $registerData->selectedRole,
                    domainStates('updated'),
                    'Profile Updated',
                    'You\'ve successfully Updated your profile'
                ),
                user: $user
            );
        }

        return $user;
    }

    protected function updateCompanyDetails(UserDetails $userDetails, RegisterData $registerData): UserDetails
    {
        $userDetails->forceFill([
            'company_name' => $registerData->companyName,
            'address' => $registerData->address,
            'gst' => $registerData->gst,
            'company_mobile_number' => null,
            'company_email' => null,
            'contact_person' => $registerData->contactPerson,
            'contact_person_email' => $registerData->contactPersonEmail,
            'contact_person_phone' => $registerData->contactPersonPhone,
            'contact_person_address' => $registerData->contactPersonAddress,
            'registration_doc' => $this->saveFile(
                $userDetails,
                $registerData->registrationDoc,
                'registration_doc',
                'user-details/registration-documents/',
            ),
        ]);

        return $userDetails;
    }

    /**
     * @throws JsonException
     */
    protected function updateCandidateJobPreferences(UserDetails $userDetails, RegisterData $registerData): UserDetails
    {
        $userDetails->forceFill([
            'job_preferences' => json_encode([
                'Type' => null,
                'Industry' => null,
                'Role' => $registerData->preferredJobRole,
                'Location' => collect($registerData->preferredJobLocation)->map(function ($location) {
                    return [
                        'value' => slugGenerator($location['value']),
                        'label' => ucfirst($location['value']),
                    ];
                }),
                'EmploymentStatus' => null,
            ], JSON_THROW_ON_ERROR | true),
        ]);

        return $userDetails;
    }
}

