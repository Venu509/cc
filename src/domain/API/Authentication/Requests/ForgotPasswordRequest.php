<?php

namespace Domain\API\Authentication\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\API\Authentication\Data\ForgotPasswordData;

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
            'via' => 'required',
            'email' => 'required_if:via,email',
            'phone' => 'required_if:via,phone',
        ];
    }

    public function data(): ForgotPasswordData
    {
        return new ForgotPasswordData(
            $this->input('via'),
            $this->input('email'),
            $this->input('phone'),
        );
    }
}
