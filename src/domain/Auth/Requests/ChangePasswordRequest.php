<?php

namespace Domain\Auth\Requests;

use Domain\Auth\Data\ChangePasswordData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'email' => 'required',
            'currentPassword' => 'required',
            'password' =>[
                'required',
                'max:20'
            ],
            'passwordConfirmation' =>[
                'required',
                'max:20'
            ],
        ];
    }

    public function data(): ChangePasswordData
    {
        return new ChangePasswordData(
            $this->input('email'),
            $this->input('currentPassword'),
            $this->input('password'),
        );
    }

    public function validations(): array
    {
        return [
            'email' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The name field is required'
                ],
            ],
            'currentPassword' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The current password field is required'
                ],
            ],
            'password' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The password field is required'
                ],
                [
                    'type' => 'min',
                    'limit' => 8,
                    'message' => 'The password must be at least 8 characters long'
                ],
                [
                    'type' => 'letters',
                    'limit' => null,
                    'message' => 'The password must contain at least one letter'
                ],
                [
                    'type' => 'numbers',
                    'limit' => null,
                    'message' => 'The password must contain at least one number'
                ],
                [
                    'type' => 'symbols',
                    'limit' => null,
                    'message' => 'The password must contain at least one symbol'
                ],
                [
                    'type' => 'uncompromised',
                    'limit' => null,
                    'message' => 'The password has appeared in a data breach and should not be used'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The password cannot exceed 20 characters'
                ],
            ],
            'passwordConfirmation' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The password field is required'
                ],
                [
                    'type' => 'min',
                    'limit' => 8,
                    'message' => 'The password must be at least 8 characters long'
                ],
                [
                    'type' => 'letters',
                    'limit' => null,
                    'message' => 'The password must contain at least one letter'
                ],
                [
                    'type' => 'numbers',
                    'limit' => null,
                    'message' => 'The password must contain at least one number'
                ],
                [
                    'type' => 'symbols',
                    'limit' => null,
                    'message' => 'The password must contain at least one symbol'
                ],
                [
                    'type' => 'uncompromised',
                    'limit' => null,
                    'message' => 'The password has appeared in a data breach and should not be used'
                ],
                [
                    'type' => 'max',
                    'limit' => 20,
                    'message' => 'The password cannot exceed 20 characters'
                ],
                [
                    'type' => 'confirmed',
                    'limit' => null,
                    'message' => 'The password confirmation does not match'
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
