<?php

namespace Domain\Role\Requests;

use Domain\Role\Data\RoleData;
use Domain\Global\Traits\Validation;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    use Validation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'displayName' => 'required|max:255',
        ];
    }

    public function data(): RoleData
    {
        return new RoleData(
            $this->input('name'),
            $this->input('displayName'),
            $this->input('permissions'),
        );
    }
}
