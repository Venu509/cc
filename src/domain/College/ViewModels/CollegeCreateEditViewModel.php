<?php

namespace Domain\College\ViewModels;

use App\Models\User;
use Domain\College\Helpers\CollegeHelper;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class CollegeCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?User $oldUser = null
    )
    {
    }

    public function college(): array
    {
        return [
            'id' => $this->oldUser?->id,
            'name' => $this->oldUser?->name,
            'via' => $this->oldUser->login_via ?? 'email',
            'registrationDoc' => $this->oldUser ? imageCheck('user-details/registration-documents/thumbnail/' . $this->getUserDetails($this->oldUser)?->registration_doc) : null,
            'registrationDocPreview' => $this->oldUser ? imageCheck('user-details/registration-documents/thumbnail/' . $this->getUserDetails($this->oldUser)?->registration_doc) : null,
            'address' => $this->getUserDetails($this->oldUser)?->address,
            'mobileNumber' => $this->oldUser?->phone,
            'email' => $this->oldUser?->email,
            'username' => $this->oldUser?->username,
            'yearsOfExistence' => $this->getUserDetails($this->oldUser)?->years_of_existence,
            'dateOfRegister' => $this->getUserDetails($this->oldUser)?->date_of_register,
            'contactPerson' => $this->getUserDetails($this->oldUser)?->contact_person,
            'contactPersonEmail' => $this->getUserDetails($this->oldUser)?->contact_person_email,
            'contactPersonPhone' => $this->getUserDetails($this->oldUser)?->contact_person_phone,
            'contactPersonAddress' => $this->getUserDetails($this->oldUser)?->contact_person_address,
            'type' => [
                'value' => $this->oldUser ? $this->oldUser->roles()->first()->name : 'government',
                'label' => $this->oldUser ? $this->oldUser->roles()->first()->display_name : 'Government',
            ],
        ];
    }

    public function types(): Collection
    {
        return (new CollegeHelper())->types();
    }

    private function getUserDetails($user)
    {
        return $user ? $user->userDetail : null;
    }
}
