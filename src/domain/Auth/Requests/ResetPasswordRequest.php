<?php

namespace Domain\Auth\Requests;

use Domain\Auth\Data\ResetPasswordData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword' => 'required',
            'password' => ['required'],
        ];
    }

    public function data(): ResetPasswordData
    {
        return new ResetPasswordData(
            $this->input('currentPassword'),
            $this->input('password'),
        );
    }
}
