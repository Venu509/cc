<?php

namespace Domain\API\Authentication\Requests;

use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;
use Domain\API\Authentication\Data\AccountVerificationCheckData;

class AccountVerificationCheckRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userId' => 'required',
        ];
    }

    public function data(): AccountVerificationCheckData
    {
        return new AccountVerificationCheckData(
            $this->input('userId'),
        );
    }
}
