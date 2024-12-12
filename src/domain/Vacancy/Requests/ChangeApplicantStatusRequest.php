<?php

namespace Domain\Vacancy\Requests;

use Domain\Global\Traits\Validation;
use Domain\User\Rules\UserExists;
use Domain\Vacancy\Data\ChangeApplicantStatusData;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Support\Helper\Helper;

class ChangeApplicantStatusRequest extends FormRequest
{
    use Helper;
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'vacancy' => [
                'required',
                new VacancyExists()
            ],
            'resume' => [
                'required',
                new UserExists()
            ],
            'status' => [
                'required',
                Rule::in(['rejected', 'shortlisted', 'viewed'])
            ],
        ];

        if($this->requestTypeCheck()) {
            return $rules;
        }

        return array_merge($rules, [
            'type' => [
                'required',
                Rule::in(['axios', 'inertia'])
            ],
            'tab' => [
                'required',
                Rule::in(['pending', 'shortlisted', 'rejected'])
            ],
        ]);
    }

    public function data(): ChangeApplicantStatusData
    {
        return new ChangeApplicantStatusData(
            $this->input('vacancy'),
            $this->input('resume'),
            $this->input('type') === 'axios',
            $this->input('status'),
            $this->input('intendedRoute'),
            $this->input('tab'),
        );
    }
}
