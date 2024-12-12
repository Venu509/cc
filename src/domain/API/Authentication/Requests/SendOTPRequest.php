<?php

namespace Domain\API\Authentication\Requests;

use Domain\API\Authentication\Data\SendOTPData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendOTPRequest extends FormRequest
{
    use Validation;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

    public function data(): SendOTPData
    {
        return new SendOTPData(
            $this->input('type') ?? 'other',
            $this->input('via'),
            $this->input('email'),
            $this->input('phone'),
        );
    }
}
