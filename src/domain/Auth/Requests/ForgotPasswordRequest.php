<?php

namespace Domain\Auth\Requests;

use Domain\Auth\Data\ForgotPasswordData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|exists:users,username',
        ];
    }

    public function data(): ForgotPasswordData
    {
        return new ForgotPasswordData(
            $this->input('username'),
        );
    }

    public function validations(): array
    {
        return [
            'username' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The username name field is required'
                ],
            ],

            'password_confirmation' => [
                [
                    'type' => 'confirmed',
                    'limit' => null,
                    'message' => 'The password confirmation does not match'
                ],
            ],
        ];
    }
}
