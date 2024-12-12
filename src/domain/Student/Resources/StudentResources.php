<?php

namespace Domain\Student\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'dob' => $this->dob,
            'mobileNumber' => $this->mobile_number,
            'email' => $this->email,
            'address' => $this->address,
            'panNumber' => $this->pan_number,
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch->name
            ],
            'aadhaarNumber' => $this->aadhaar_number,
            'qualification' => $this->qualification,
            'gender' => $this->gender,
            'maritalStatus' => $this->marital_status,
            'studentId' => $this->student_id,
            'profilePicture' => imageCheck('students/thumbnail/' . $this->image),
            'resume' => $this->resume ? imageCheck('resumes/thumbnail/' .  $this->resume) : null,
        ];
    }
}
