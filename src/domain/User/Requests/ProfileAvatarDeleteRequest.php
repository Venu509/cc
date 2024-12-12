<?php

namespace Domain\User\Requests;

use Domain\Global\Traits\Validation;
use Domain\User\Data\ProfileAvatarDeleteData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileAvatarDeleteRequest extends FormRequest
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
                Rule::in(['dealer', 'driver', 'customer'])
            ],
            'user' => [
                'required',
            ],
        ];
    }

    public function data(): ProfileAvatarDeleteData
    {
        return new ProfileAvatarDeleteData(
            $this->input('role'),
            $this->input('user'),
        );
    }
}
