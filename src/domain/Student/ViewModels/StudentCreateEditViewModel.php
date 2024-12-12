<?php

namespace Domain\Student\ViewModels;

use Domain\Branch\Models\Branch;
use Domain\Student\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\ViewModels\ViewModel;

class StudentCreateEditViewModel extends ViewModel
{
    public function __construct(
        public ?Student $oldStudent = null
    )
    {
    }

    public function student(): array
    {
        return [
            'id' => $this->oldStudent?->id,
            'firstName' => $this->oldStudent?->first_name,
            'lastName' => $this->oldStudent?->last_name,
            'dateOfBirth' => $this->oldStudent?->dob,
            'mobileNumber' => $this->oldStudent?->mobile_number,
            'email' => $this->oldStudent?->email,
            'address' => $this->oldStudent?->address,
            'panNumber' => $this->oldStudent?->pan_number,
            'branch' => [
                'value' => $this->oldStudent?->branch_id,
                'label' => $this->oldStudent?->branch->name,
            ],
            'aadhaarNumber' => $this->oldStudent?->aadhaar_number,
            'qualification' => $this->oldStudent?->qualification,
            'gender' => $this->oldStudent?->gender,
            'maritalStatus' => $this->oldStudent?->marital_status,
            'studentId' => $this->oldStudent?->student_id,
            'imagePreview' => $this->oldStudent ? imageCheck('students/thumbnail/' . $this->oldStudent->image) : null,
            'resumePreview' => $this->oldStudent ? imageCheck('resumes/thumbnail/' . $this->oldStudent->resume) : null,
            'profilePicture' => $this->oldStudent ? imageCheck('students/thumbnail/' . $this->oldStudent->image) : null,
            'image' =>$this->oldStudent ?? null,
            'resume' => $this->oldStudent ? imageCheck('resumes/thumbnail/' .  $this->oldStudent->resume) : null,

        ];
    }

    public function branches(): Collection
    {
        return Branch::query()->where('added_by', Auth::user()->id)->select(
            'name as label',
            'id as value'
        )->get();
    }
}
