<?php

namespace Domain\API\Authentication\Requests;

use Domain\API\Authentication\Data\AuthCheckData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthCheckRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'role' => [
//                'required',
//                Rule::in([
//                    'salesman',
//                    'supervisor',
//                    'worker',
//                    'indian',
//                    'american',
//                ])
//            ],
            'type' => [
                'required',
                Rule::in(['register', 'other'])
            ],
            'via' => [
                'required',
                Rule::in(['email', 'phone'])
            ],
            'email' => 'required_if:via,email',
            'phone' => 'required_if:via,phone',
        ];
    }

    public function data(): AuthCheckData
    {
        return new AuthCheckData(
            $this->input('role') ?? 'company',
            $this->input('type') ?? 'other',
            $this->input('via'),
            $this->input('email'),
            $this->input('phone'),
        );
    }
}
