<?php

namespace Domain\Auth\Requests;

use Domain\Auth\Data\SetPasswordData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetPasswordRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'token' => [
                'required',
            ],
            'password' => ['required'],
        ];
    }

    public function data(): SetPasswordData
    {
        return new SetPasswordData(
            $this->input('email'),
            $this->input('password'),
            $this->input('token'),
        );
    }
}
