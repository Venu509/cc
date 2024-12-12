<?php

namespace Domain\College\Resources;

use Domain\Global\Helpers\EmployeeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonException;
use Support\Helper\Helper;

class CollegeResources extends JsonResource
{
    use Helper;

    public function toArray(Request $request): array
    {
        $employeeHelper = new EmployeeHelper();

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
            'userDetail' => $this->userDetail($this),
        ];
    }

    /**
     * @throws JsonException
     */
    private function userDetail($user): array
    {
        return [
            'id' => $user->userDetail->id,
            'fullName' => $user->userDetail->full_name,
            'gender' => $user->userDetail->gender,
            'address' => $user->userDetail->address,
            'noOfExperiences' => $user->userDetail->no_of_experiences,
            'yearsOfExistence' => $user->userDetail->years_of_existence,
            'companyName' => $user->userDetail->company_name,
            'companyMobileNumber' => $user->userDetail->company_mobile_number,
            'companyEmail' => $user->userDetail->company_email,
            'contactPerson' => $user->userDetail->contact_person,
            'contactPersonEmail' => $user->userDetail->contact_person_email,
            'contactPersonPhone' => $user->userDetail->contact_person_phone,
            'contactPersonAddress' => $user->userDetail->contact_person_address,
            'dateOfRegister' => $user->userDetail->date_of_register,
            'registrationDoc' => $user->userDetail->registration_doc ? imageCheck('user-details/registration-documents/thumbnail/' . $user->userDetail->registration_doc) : null,
        ];
    }
}
