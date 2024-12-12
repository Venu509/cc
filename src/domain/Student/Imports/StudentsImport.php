<?php

namespace Domain\Student\Imports;

use Carbon\Carbon;
use Domain\Branch\Models\Branch;
use Domain\Student\Actions\StoreStudentAction;
use Domain\Student\Data\StudentData;
use Domain\Student\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class StudentsImport implements ToCollection, WithHeadingRow
{
    public function __construct()
    {
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $item) {
            if ($this->hasRequiredColumns($item)) {
                $studentBuilder = Student::query()->where('student_id', $item['student_id']);

                $student = $studentBuilder->where('added_by', Auth::id())->first();

                if (!$student) {
                    $student = new Student();
                }

                $studentData = new StudentData(
                    id: $student->id ?? null,
                    firstName: $item['first_name'],
                    lastName: $item['last_name'],
                    dateOfBirth: $this->parseDate($item['dob']),
                    mobileNumber: $item['mobile_number'],
                    email: $item['email'],
                    address: $item['address'],
                    resume: null,
                    panNumber: $item['pan_number'] ?: null,
                    aadhaarNumber: $item['aadhaar_number'],
                    qualification: $item['qualification'],
                    gender: slugGenerator($item['gender']),
                    maritalStatus: slugGenerator($item['marital_status']),
                    profilePicture: null,
                    branch: ['value' => $this->getBranchId($item['branch'])],
                    studentId: $item['student_id'],
                );

                (new StoreStudentAction())->execute($studentData, $student);
            }
        }
    }

    private function parseDate($date): Carbon
    {
        return Carbon::parse('1899-12-30')->addDays($date);
    }

    private function getBranchId($branchName)
    {
        $branch = Branch::query()->where('added_by', Auth::id())->where('name', $branchName);
        if ($branch->exists()){
            return $branch->first()->id;
        }

        $newBranch = new Branch();

        $newBranch->name = $branchName;
        $newBranch->description = $branchName;
        $newBranch->added_by = Auth::id();
        $newBranch->modified_by = Auth::id();

        $newBranch->save();

        return $newBranch->id;
    }

    private function hasRequiredColumns($item): bool
    {
        $requiredColumns = [
            'first_name', 'last_name', 'dob', 'mobile_number', 'email', 'address',
            'student_id', 'aadhaar_number', 'qualification', 'gender', 'marital_status', 'branch'
        ];

        foreach ($requiredColumns as $column) {
            if (empty($item[$column])) {
                return false;
            }
        }

        return true;
    }
}
