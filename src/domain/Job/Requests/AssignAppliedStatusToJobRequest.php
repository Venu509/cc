<?php

namespace Domain\Job\Requests;

use Domain\Global\Traits\Validation;
use Domain\Job\Data\AssignAppliedStatusToJobData;
use Domain\User\Rules\UserExists;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignAppliedStatusToJobRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                new VacancyExists()
            ],
            'candidate' => [
                'required',
                new UserExists()
            ],
            'role' => [
                'required',
                Rule::in(['master', 'admin', 'candidate'])
            ],
            'isApplied' => [
                'required',
                Rule::in(['yes', 'no'])
            ],
        ];
    }

    public function data(): AssignAppliedStatusToJobData
    {
        return new AssignAppliedStatusToJobData(
            $this->input('id'),
            $this->input('candidate'),
            $this->input('role'),
            $this->input('isApplied') === 'yes',
        );
    }
}