<?php

namespace Domain\Vacancy\Requests;

use Domain\Global\Traits\Validation;
use Domain\User\Rules\UserExists;
use Domain\Vacancy\Data\ChangeApplicantStatusData;
use Domain\Vacancy\Data\DeleteVacancyData;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Support\Helper\Helper;

class DeleteVacancyRequest extends FormRequest
{
    use Helper;
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vacancy' => [
                'required',
                new VacancyExists()
            ],
            'user' => [
                'required',
                new UserExists()
            ],
            'role' => [
                'required',
                Rule::in(['company', 'admin', 'master'])
            ],
        ];
    }

    public function data(): DeleteVacancyData
    {
        return new DeleteVacancyData(
            $this->input('vacancy'),
            (int)$this->input('user'),
            $this->input('role'),
        );
    }
}
