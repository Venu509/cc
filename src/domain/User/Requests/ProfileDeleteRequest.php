<?php

namespace Domain\User\Requests;

use Domain\API\Authentication\Rules\CheckOldPassword;
use Domain\Global\Traits\Validation;
use Domain\User\Data\ProfileDeleteData;
use Domain\User\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileDeleteRequest extends FormRequest
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
                Rule::in(['candidate', 'company', 'government', 'institution', 'marketing'])
            ],
            'user' => [
                'required',
                new UserExists()
            ],
            'currentPassword' => [
                'required',
                new CheckOldPassword(findUserById($this->input('user'))),
            ],
        ];
    }

    public function data(): ProfileDeleteData
    {
        return new ProfileDeleteData(
            $this->input('role'),
            $this->input('user'),
            $this->input('currentPassword'),
        );
    }
}
