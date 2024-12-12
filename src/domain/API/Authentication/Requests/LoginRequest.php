<?php

namespace Domain\API\Authentication\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\API\Authentication\Data\LoginData;
use Illuminate\Validation\Rule;

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
            'via' => [
                'required',
                Rule::in(['email', 'phone'])
            ],
            'email' => 'required_if:via,email',
            'phone' => 'required_if:via,phone',
            'password' => [
                'required_if:hasPassword,yes'
            ],
            'hasPassword' => [
                'required',
                Rule::in(['yes', 'no'])
            ],
            'code' => [
                'required_if:hasPassword,no|numeric',
            ],
            'deviceToken' => [
                'required',
                'string',
            ],
            'deviceType' => [
                'required',
                Rule::in(['android', 'ios', 'web'])
            ],
        ];
    }

    public function data(): LoginData
    {
        return new LoginData(
            $this->input('via'),
            $this->input('email'),
            $this->input('phone'),
            $this->input('password'),
            $this->input('hasPassword') === 'yes',
            $this->input('code'),
            $this->input('deviceToken'),
            $this->input('deviceType'),
        );
    }
}
