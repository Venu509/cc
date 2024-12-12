<?php

namespace Domain\Branch\Requests;

use Domain\Branch\Data\BranchDeleteData;
use Domain\Branch\Rules\BranchExists;
use Domain\Global\Traits\Validation;
use Domain\User\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;

class BranchDeleteRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => [
                'required',
                new BranchExists()
            ],
            'user' => [
                'required',
                new UserExists()
            ],
        ];
    }

    public function data(): BranchDeleteData
    {
        return new BranchDeleteData(
            $this->input('id'),
            $this->input('user'),
        );
    }
}
