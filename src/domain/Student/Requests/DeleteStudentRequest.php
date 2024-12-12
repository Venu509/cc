<?php

namespace Domain\Student\Requests;

use Domain\Global\Traits\Validation;
use Domain\Student\Data\DeleteStudentData;
use Domain\Student\Rules\StudentExists;
use Domain\Student\Rules\ValidateStudentIdsForTypeRule;
use Domain\User\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteStudentRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => [
                'required',
                new StudentExists(),
                new ValidateStudentIdsForTypeRule($this->input('type'))
            ],
            'user' => [
                'required',
                new UserExists(),
            ],
            'type' => [
                'required',
                Rule::in(['single', 'bulk']),
            ],
            'role' => [
                'required',
                Rule::in(['government', 'institution']),
            ],
        ];
    }

    public function data(): DeleteStudentData
    {
        return new DeleteStudentData(
            $this->input('ids'),
            $this->input('role'),
            $this->input('user'),
            $this->input('type'),
        );
    }
}
