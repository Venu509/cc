<?php

namespace Domain\Attendance\Requests;

use Domain\Attendance\Data\AttendanceData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                Rule::in([
                    'marketing',
                ])
            ],
            'isClockIn' => [
                'required',
                Rule::in(['yes', 'no'])
            ],
            'coordinates' => [
                'required',
                'array',
            ],
        ];
    }

    public function data(): AttendanceData
    {
        return new AttendanceData(
            $this->input('id'),
            $this->input('role'),
            $this->input('isClockIn') === 'yes',
            $this->input('coordinates'),
        );
    }
}
