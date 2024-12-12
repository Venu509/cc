<?php

namespace Domain\API\Authentication\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Domain\API\Authentication\Rules\CheckOldPassword;
use Domain\API\Authentication\Data\ChangePasswordData;

class ChangePasswordRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user' => 'required',
            'oldPassword' => [
                'sometimes',
                'required',
                new CheckOldPassword(),
            ],
            'password' => [
                'required',
                'confirmed',
                app()->isLocal() ? null : Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function data(): ChangePasswordData
    {
        return new ChangePasswordData(
            $this->input('user'),
            $this->input('oldPassword'),
            $this->input('password'),
        );
    }
}
