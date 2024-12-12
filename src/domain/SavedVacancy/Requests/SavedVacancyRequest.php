<?php

namespace Domain\SavedVacancy\Requests;

use Domain\Global\Traits\Validation;
use Domain\SavedVacancy\Data\SavedVacancyData;
use Domain\User\Rules\UserExists;
use Domain\Vacancy\Rules\VacancyExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavedVacancyRequest extends FormRequest
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
    public function data(): SavedVacancyData
    {
        return new SavedVacancyData(
            $this->input('vacancyId'),
            $this->input('role'),
            $this->input('candidate'),
        );
    }

}
