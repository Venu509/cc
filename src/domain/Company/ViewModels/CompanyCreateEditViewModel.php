<?php

namespace Domain\Company\ViewModels;

use App\Models\User;
use Spatie\ViewModels\ViewModel;

class CompanyCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?User $oldUser = null
    )
    {
    }

    public function company(): array
    {
        $userDetails = $this->getUserDetails($this->oldUser);

        return [
            'id' => $this->oldUser?->id,
            'tab' => 'personal-details',
            'email' => $this->oldUser?->email,
            'via' => $this->oldUser->login_via ?? 'email',
            'mobileNumber' => $this->oldUser?->phone,
            'username' => $this->oldUser?->username,
            'alternativeNumber' => $this->oldUser?->alternative_number,
            'fullName' => $userDetails?->full_name,
            'dob' => $userDetails?->dob,
            'gender' => $userDetails?->gender,
            'maritalStatus' => $userDetails?->marital_status,
            'street' => $userDetails?->street,
            'city' => $userDetails?->city,
            'state' => $userDetails?->state,
            'postalCode' => $userDetails?->postal_code,
            'address' => $userDetails?->address,
            'gst' => $userDetails?->gst,
            'country' => $userDetails?->country,
            'type' => [
                'value' => 'company',
                'label' => 'Company',
            ],

            'avatar' => imageCheck('user-details/avatars/thumbnails/' . $this->oldUser?->avatar),

            'companyName' => $userDetails?->company_name,
            'companyMobileNumber' => $userDetails?->company_mobile_number,
            'contactPerson' => $userDetails?->contact_person,
            'contactPersonEmail' => $userDetails?->contact_person_email,
            'contactPersonPhone' => $userDetails?->contact_person_phone,
            'contactPersonAddress' => $userDetails?->contact_person_address,
        ];
    }

    private function getUserDetails($user)
    {
        return $user ? $user->userDetail : null;
    }
}
