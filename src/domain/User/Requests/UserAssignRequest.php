<?php

namespace Domain\User\Requests;

use Domain\Global\Traits\Validation;
use Domain\User\Data\UserAssignData;
use Illuminate\Foundation\Http\FormRequest;

class UserAssignRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required',
            'role' => 'required',
            'region' => 'required_if:role,regional-sales-manager',
            'area' => 'required_if:role,area-sales-manager',
            'city' => 'required_if:role,sales-officer',
            'topLevelUser' => 'required_unless:role,regional-sales-manager',
        ];
    }

    public function data(): UserAssignData
    {
        return new UserAssignData(
            $this->input('id'),
            $this->input('role'),
            $this->input('region'),
            $this->input('area'),
            $this->input('city'),
            $this->input('topLevelUser'),
        );
    }
}
