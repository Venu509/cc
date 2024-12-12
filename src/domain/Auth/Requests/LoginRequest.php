<?php

namespace Domain\Auth\Requests;

use Domain\Auth\Data\LoginData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
            'intendedRoute' => 'required|string',
        ];
    }

    public function data(): LoginData
    {
        return new LoginData(
            $this->input('username'),
            $this->input('password'),
            $this->input('intendedRoute'),
        );
    }

    public function validations(): array
    {
        return [
            'username' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The username field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'The username field should be type of string'
                ],
            ],
            'password' => [
                [
                    'type' => 'required',
                    'limit' => null,
                    'message' => 'The password field is required'
                ],
                [
                    'type' => 'string',
                    'limit' => null,
                    'message' => 'The password field should be type of string'
                ],
            ],
        ];
    }
}