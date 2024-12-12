<?php

namespace Domain\Vacancy\Requests;

use Domain\Global\Rules\CheckIfAdditionalQuestionsArrayContainAtLeastOneQuestion;
use Domain\Global\Rules\DateRule;
use Domain\Global\Rules\MultipleDropdownValidate;
use Domain\Global\Rules\TextOnly;
use Domain\Global\Traits\Validation;
use Domain\User\Rules\UserExists;
use Domain\Vacancy\Data\UserVacancyData;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserVacancyRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vacancyId' => [
                'required',
                new VacancyExists()
            ],
            'role' => [
                'required',
                Rule::in(['master', 'admin', 'candidate'])
            ],
            'candidate' => [
                'required',
                new UserExists()
            ],
        ];
    }

    public function data(): UserVacancyData
    {
        return new UserVacancyData(
            $this->input('vacancyId'),
            $this->input('role'),
            $this->input('candidate'),
            $this->input('answers'),
        );
    }
}
