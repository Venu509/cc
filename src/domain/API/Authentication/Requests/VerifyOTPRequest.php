<?php

namespace Domain\API\Authentication\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\API\Authentication\Data\VerifyOTPData;
use Illuminate\Validation\Rule;

class VerifyOTPRequest extends FormRequest
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
            'code' => 'required',
            'via' => [
                'required',
                Rule::in(['email', 'phone'])
            ],
            'email' => 'required_if:via,email',
            'phone' => 'required_if:via,phone',
        ];
    }

    public function data(): VerifyOTPData
    {
        return new VerifyOTPData(
            $this->input('type') ?? 'other',
            $this->input('code'),
            $this->input('via'),
            $this->input('email'),
            $this->input('phone'),
        );
    }
}
