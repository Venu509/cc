<?php

namespace Domain\Student\Requests;

use Domain\Global\Traits\Validation;
use Domain\Student\Data\StudentBulkUploadData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentBulkUploadRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    $fileType = $this->input('fileUploadType');

                    if ($fileType === 'data') {
                        $allowedMimes = ['xlsx', 'xls', 'xlsm', 'csv'];
                    } elseif ($fileType === 'assets') {
                        $allowedMimes = ['zip'];
                    } else {
                        $fail('Invalid file upload type.');
                        return;
                    }

                    if (!in_array($value->getClientOriginalExtension(), $allowedMimes, true)) {
                        $fail("The $attribute must be a file of type: " . implode(', ', $allowedMimes));
                    }
                },
                'max:10240',  // Maximum file size validation (10 MB)
            ],
            'fileUploadType' => [
                'required',
                Rule::in(['data', 'assets'])
            ],
        ];
    }

    public function data(): StudentBulkUploadData
    {
        return new StudentBulkUploadData(
            $this->file('file'),
            $this->input('fileUploadType'),
        );
    }
}
