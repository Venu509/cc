<?php

namespace Domain\Student\Actions;

use Domain\Student\Data\StudentData;
use Domain\Student\Models\Student;
use Illuminate\Support\Facades\Auth;
use Support\Helper\Helper;

class StoreStudentAction
{
    use Helper;

    public function execute(
        StudentData $studentData,
        Student $student = new Student()
    ): Student {

        $student->forceFill([
            'first_name' => $studentData->firstName,
            'last_name' => $studentData->lastName,
            'dob' => $studentData->dateOfBirth,
            'mobile_number' => $studentData->mobileNumber,
            'email' => $studentData->email,
            'address' => $studentData->address,
            'pan_number' => $studentData->panNumber,
            'branch_id' => $studentData->branch['value'],
            'aadhaar_number' => $studentData->aadhaarNumber,
            'qualification' => $studentData->qualification,
            'gender' => $studentData->gender,
            'marital_status' => $studentData->maritalStatus,
            'student_id' => $studentData->studentId,
            'image' => $this->saveFile(
                $student,
                $studentData->profilePicture,
                'image',
                'students/',
            ),
            'resume' => $this->saveFile(
                $student,
                $studentData->resume,
                'resume',
                'resumes/',
            ),
            'modified_by' => Auth::user()->id,
            'added_by' => $student->added_by ?? Auth::user()->id,
        ]);


        $student->save();

        $student->refresh();

        return $student;
    }
}
